<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsahaResource\Pages;
use App\Forms\Components\LeafletMap;
use App\Models\Usaha;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class UsahaResource extends Resource
{
    protected static ?string $model = Usaha::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Data Usaha';
    protected static ?string $modelLabel = 'Usaha';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        // TAB 1: Informasi Dasar
                        Forms\Components\Tabs\Tab::make('Informasi Dasar')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Select::make('kategori_id')
                                    ->label('Kategori Usaha')
                                    ->relationship('kategori', 'nama')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                Forms\Components\Select::make('kelurahan_id')
                                    ->label('Kelurahan')
                                    ->relationship('kelurahan', 'nama')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Usaha')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                                        $set('slug', Str::slug($state))
                                    )
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->columnSpan(2),

                                Forms\Components\Textarea::make('deskripsi')
                                    ->label('Deskripsi Usaha')
                                    ->rows(4)
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('alamat')
                                    ->label('Alamat Lengkap')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'tidak_aktif' => 'Tidak Aktif',
                                        'tutup_sementara' => 'Tutup Sementara',
                                        'tutup_permanen' => 'Tutup Permanen',
                                    ])
                                    ->default('aktif')
                                    ->required()
                                    ->columnSpan(1),

                                Forms\Components\Toggle::make('is_verified')
                                    ->label('Terverifikasi')
                                    ->columnSpan(1),
                            ])->columns(2),

                      // TAB 2: Lokasi (PERBAIKAN)
Forms\Components\Tabs\Tab::make('Lokasi')
    ->icon('heroicon-o-map-pin')
    ->schema([
        // PETA
        LeafletMap::make('location')
            ->label('Pilih Lokasi di Peta')
            ->defaultLocation(-6.2088, 106.8456)
            ->defaultZoom(13)
            ->searchable()
            ->draggable()
            ->columnSpanFull(),

        // KOORDINAT
        Forms\Components\Section::make('Koordinat Lokasi')
            ->description('Koordinat akan terisi otomatis saat Anda memilih lokasi di peta')
            ->schema([
                Forms\Components\TextInput::make('latitude')
                    ->label('Latitude')
                    ->required()
                    ->numeric()
                    ->step(0.00000001)
                    ->placeholder('-6.2088')
                    ->reactive()
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Akan terisi otomatis dari peta'),

                Forms\Components\TextInput::make('longitude')
                    ->label('Longitude')
                    ->required()
                    ->numeric()
                    ->step(0.00000001)
                    ->placeholder('106.8456')
                    ->reactive()
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Akan terisi otomatis dari peta'),
            ])->columns(2),

        // PREVIEW GOOGLE MAPS
        Forms\Components\Section::make('Preview & Navigasi')
            ->schema([
                Forms\Components\Placeholder::make('google_maps_preview')
                    ->label('Google Maps Link')
                    ->content(function ($get, $record) {
                        $lat = $get('latitude') ?? $record?->latitude;
                        $lng = $get('longitude') ?? $record?->longitude;
                        
                        if ($lat && $lng) {
                            $url = "https://www.google.com/maps?q={$lat},{$lng}";
                            return new \Illuminate\Support\HtmlString(
                                '<div class="flex items-center gap-3">
                                    <a href="' . $url . '" target="_blank" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Buka di Google Maps
                                    </a>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        Koordinat: ' . number_format($lat, 6) . ', ' . number_format($lng, 6) . '
                                    </span>
                                </div>'
                            );
                        }
                        
                        return new \Illuminate\Support\HtmlString(
                            '<p class="text-sm text-gray-500 dark:text-gray-400">
                                Pilih lokasi di peta terlebih dahulu untuk melihat preview
                            </p>'
                        );
                    })
                    ->columnSpanFull(),
            ]),
    ]),

                        // TAB 3: Kontak
                        Forms\Components\Tabs\Tab::make('Kontak')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Forms\Components\TextInput::make('telepon')
                                    ->label('Telepon')
                                    ->tel()
                                    ->maxLength(255)
                                    ->placeholder('08123456789')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('whatsapp')
                                    ->label('WhatsApp')
                                    ->tel()
                                    ->maxLength(255)
                                    ->placeholder('628123456789')
                                    ->helperText('Format: 628xxx (tanpa +)')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('website')
                                    ->label('Website')
                                    ->url()
                                    ->maxLength(255)
                                    ->placeholder('https://www.contoh.com')
                                    ->columnSpan(1),
                            ])->columns(2),

                        // TAB 4: Jam Operasional
                        Forms\Components\Tabs\Tab::make('Jam Operasional')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Forms\Components\TimePicker::make('jam_buka')
                                    ->label('Jam Buka')
                                    ->seconds(false)
                                    ->columnSpan(1),

                                Forms\Components\TimePicker::make('jam_tutup')
                                    ->label('Jam Tutup')
                                    ->seconds(false)
                                    ->columnSpan(1),

                                Forms\Components\CheckboxList::make('hari_operasional')
                                    ->label('Hari Operasional')
                                    ->options([
                                        'senin' => 'Senin',
                                        'selasa' => 'Selasa',
                                        'rabu' => 'Rabu',
                                        'kamis' => 'Kamis',
                                        'jumat' => 'Jumat',
                                        'sabtu' => 'Sabtu',
                                        'minggu' => 'Minggu',
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // TAB 5: Pemilik
                        Forms\Components\Tabs\Tab::make('Data Pemilik')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\TextInput::make('nama_pemilik')
                                    ->label('Nama Pemilik/Penanggung Jawab')
                                    ->maxLength(255)
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('nik_pemilik')
                                    ->label('NIK Pemilik')
                                    ->maxLength(16)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('telepon_pemilik')
                                    ->label('Telepon Pemilik')
                                    ->tel()
                                    ->maxLength(255)
                                    ->columnSpan(1),
                            ])->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->alamat)
                    ->wrap(),

                Tables\Columns\TextColumn::make('kategori.nama')
                    ->badge()
                    ->color(fn ($record) => $record->kategori->color ?? 'gray')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelurahan.nama')
                    ->label('Kelurahan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('telepon')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean()
                    ->label('Verifikasi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak_aktif' => 'danger',
                        'tutup_sementara' => 'warning',
                        'tutup_permanen' => 'danger',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('kelurahan_id')
                    ->label('Kelurahan')
                    ->relationship('kelurahan', 'nama')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak_aktif' => 'Tidak Aktif',
                        'tutup_sementara' => 'Tutup Sementara',
                        'tutup_permanen' => 'Tutup Permanen',
                    ]),

                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Verifikasi')
                    ->placeholder('Semua')
                    ->trueLabel('Terverifikasi')
                    ->falseLabel('Belum Verifikasi'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsahas::route('/'),
            'create' => Pages\CreateUsaha::route('/create'),
            // 'view' => Pages\ViewUsaha::route('/{record}'),
            'edit' => Pages\EditUsaha::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_verified', false)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('is_verified', false)->count() > 0 
            ? 'warning' 
            : 'success';
    }
}