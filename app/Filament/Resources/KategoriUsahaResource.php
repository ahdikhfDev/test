<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriUsahaResource\Pages;
use App\Models\KategoriUsaha;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class KategoriUsahaResource extends Resource
{
    protected static ?string $model = KategoriUsaha::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kategori Usaha';
    protected static ?string $modelLabel = 'Kategori Usaha';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kategori')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                                $set('slug', Str::slug($state))
                            )
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('icon')
                            ->default('heroicon-o-building-storefront')
                            ->helperText('Contoh: heroicon-o-cake, heroicon-o-shopping-bag')
                            ->columnSpan(1),

                        Forms\Components\ColorPicker::make('color')
                            ->default('#3b82f6')
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('urutan')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->columnSpan(1),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('icon')
                    ->icon(fn ($record) => $record->icon)
                    ->color(fn ($record) => $record->color)
                    ->label('Icon'),

                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\ColorColumn::make('color')
                    ->label('Warna'),

                Tables\Columns\TextColumn::make('usahas_count')
                    ->counts('usahas')
                    ->label('Jumlah Usaha')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('urutan')
                    ->sortable()
                    ->label('Urutan'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
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
            ->defaultSort('urutan');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoriUsahas::route('/'),
            'create' => Pages\CreateKategoriUsaha::route('/create'),
            'edit' => Pages\EditKategoriUsaha::route('/{record}/edit'),
        ];
    }
}