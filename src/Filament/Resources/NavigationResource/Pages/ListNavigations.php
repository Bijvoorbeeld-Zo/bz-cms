<?php

namespace JornBoerema\BzCms\Filament\Resources\NavigationResource\Pages;

use JornBoerema\BzCMS\Filament\Resources\NavigationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNavigations extends ListRecords
{
    protected static string $resource = NavigationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
