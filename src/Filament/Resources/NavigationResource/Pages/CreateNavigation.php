<?php

namespace JornBoerema\BzCms\Filament\Resources\NavigationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use JornBoerema\BzCMS\Filament\Resources\NavigationResource;

class CreateNavigation extends CreateRecord
{
    protected static string $resource = NavigationResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
