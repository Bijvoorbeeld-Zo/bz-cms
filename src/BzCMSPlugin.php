<?php

namespace JornBoerema\BzCMS;

use Filament\Contracts\Plugin;
use JornBoerema\BzCMS\Filament\Resources\NavigationResource;
use JornBoerema\BzCMS\Filament\Resources\PageResource;

class BzCMSPlugin implements Plugin
{

    public function getId(): string
    {
        return 'bz-cms';
    }

    public function register(\Filament\Panel $panel): void
    {
        $panel->resources([
            PageResource::class,
            NavigationResource::class,
        ]);
    }

    public function boot(\Filament\Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static;
    }
}
