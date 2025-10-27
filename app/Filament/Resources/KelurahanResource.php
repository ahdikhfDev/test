<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelurahanResource\Pages;
use App\Models\Kelurahan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KelurahanResource extends Resource
{
    protected static ?string $model = Kelurahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Kelurahan';
    protected static ?string $modelLabel = 'Kelurahan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kelurahan')
                    ->schema([
                        Forms\Components\Select::make('kecamatan_id')
                            ->label('Kecamatan')
                            ->relationship('kecamatan', 'nama')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('kode')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Koordinat Pusat')
                    ->schema([
                        Forms\Components\TextInput::make('center_lat')
                            ->label('Latitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('center_lng')
                            ->label('Longitude')
                            ->numeric()
                            ->step(0.00000001)
                            ->columnSpan(1),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kecamatan.nama')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('usahas_count')
                    ->counts('usahas')
                    ->label('Jumlah Usaha')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kecamatan_id')
                    ->label('Kecamatan')
                    ->relationship('kecamatan', 'nama')
                    ->searchable()
                    ->preload(),

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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelurahans::route('/'),
            'create' => Pages\CreateKelurahan::route('/create'),
            'edit' => Pages\EditKelurahan::route('/{record}/edit'),
        ];
    }
}