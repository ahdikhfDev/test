<?php

namespace App\Filament\Resources\KategoriUsahaResource\Pages;

use App\Filament\Resources\KategoriUsahaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriUsahas extends ListRecords
{
    protected static string $resource = KategoriUsahaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
