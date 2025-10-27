<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KecamatanResource\Pages;
// use App\Filament\Resources\KecamatanResource\RelationManagers\KelurahanRelationManager;
use App\Models\Kecamatan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KecamatanResource extends Resource
{
    protected static ?string $model = Kecamatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Data Kecamatan';
    protected static ?string $modelLabel = 'Kecamatan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Kecamatan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Kecamatan')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required()
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make('Koordinat Pusat Kecamatan')
                    ->description('Tentukan titik pusat kecamatan untuk peta')
                    ->schema([
                        Forms\Components\TextInput::make('center_lat')
                            ->label('Latitude Pusat')
                            ->numeric()
                            ->step(0.00000001)
                            ->placeholder('-6.2088')
                            ->helperText('Koordinat latitude pusat kecamatan')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('center_lng')
                            ->label('Longitude Pusat')
                            ->numeric()
                            ->step(0.00000001)
                            ->placeholder('106.8456')
                            ->helperText('Koordinat longitude pusat kecamatan')
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Section::make('Batas Wilayah (Polygon)')
                    ->description('Data koordinat batas wilayah kecamatan dalam format JSON')
                    ->schema([
                        Forms\Components\Textarea::make('boundary_coordinates')
                            ->label('Koordinat Batas Wilayah')
                            ->rows(6)
                            ->placeholder('[[-6.xxx, 106.xxx], [-6.yyy, 106.yyy], ...]')
                            ->helperText('Format: Array of [latitude, longitude] pairs dalam JSON')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Kecamatan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('kelurahans_count')
                    ->counts('kelurahans')
                    ->label('Jumlah Kelurahan')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('center_lat')
                    ->label('Lat')
                    ->numeric(decimalPlaces: 6)
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('center_lng')
                    ->label('Lng')
                    ->numeric(decimalPlaces: 6)
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('nama', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            // KelurahanRelationManager::class, // Uncomment setelah file dibuat
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKecamatans::route('/'),
            'create' => Pages\CreateKecamatan::route('/create'),
            'edit' => Pages\EditKecamatan::route('/{record}/edit'),
            // 'view' => Pages\ViewKecamatan::route('/{record}'), // Uncomment setelah file dibuat
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}