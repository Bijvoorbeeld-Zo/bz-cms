<?php

namespace JornBoerema\BzCMS;

use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use JornBoerema\BzCMS\Filament\Resources\NavigationResource;
use JornBoerema\BzCMS\Filament\Resources\PageResource;

class BzCMSPlugin implements Plugin
{

    private static function isPageResourcePublished(): bool
    {
        $roleResourcePath = app_path((string) Str::of('Filament\\Resources\\PageResource.php')->replace('\\', '/'));

        $filesystem = new Filesystem;

        return (bool) $filesystem->exists($roleResourcePath);
    }

    private static function isNavResourcePublished(): bool
    {
        $roleResourcePath = app_path((string) Str::of('Filament\\Resources\\NavigationResource.php')->replace('\\', '/'));

        $filesystem = new Filesystem;

        return (bool) $filesystem->exists($roleResourcePath);
    }

    public function getId(): string
    {
        return 'bz-cms';
    }

    public function register(\Filament\Panel $panel): void
    {
        if (! self::isPageResourcePublished()) {
            $panel->resources([
                PageResource::class,
            ]);
        }
        if (! self::isNavResourcePublished()) {
            $panel->resources([
                NavigationResource::class,
            ]);
        }
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
