<?php

namespace App\Filament\Resources\UsahaResource\Pages;

use App\Filament\Resources\UsahaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsahas extends ListRecords
{
    protected static string $resource = UsahaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
