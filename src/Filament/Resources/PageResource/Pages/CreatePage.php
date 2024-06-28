<?php

namespace JornBoerema\BzCms\Filament\Resources\PageResource\Pages;

use JornBoerema\BzCMS\Filament\Resources\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
