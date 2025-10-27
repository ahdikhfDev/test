<?php

namespace App\Filament\Resources\KategoriUsahaResource\Pages;

use App\Filament\Resources\KategoriUsahaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriUsaha extends EditRecord
{
    protected static string $resource = KategoriUsahaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
