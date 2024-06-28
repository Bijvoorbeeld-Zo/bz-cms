<?php

namespace JornBoerema\BzCMS;

use JornBoerema\BzCMS\Commands\CreateCMSBlockCommand;
use JornBoerema\BzUserManagement\Livewire\AcceptInvitation;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class BzCMSServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('bz-cms')
            ->hasViews()
            ->hasConfigFile()
            ->hasRoute('web')
            ->hasMigrations('create_cms_tables')
            ->hasCommand(CreateCMSBlockCommand::class)
            ->hasInstallCommand(function (InstallCommand $installCommand) {
                $installCommand->publishMigrations();
                $installCommand->publishConfigFile();
            });
    }

    public function boot()
    {
        parent::boot();

        Livewire::component('jorn-boerema.bz-cms.livewire.page_template', AcceptInvitation::class);
    }
}
