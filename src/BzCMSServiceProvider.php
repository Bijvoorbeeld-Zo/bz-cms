<?php

namespace JornBoerema\BzCMS;

use Illuminate\Support\Facades\Schema;
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
            ->hasTranslations()
            ->hasCommand(CreateCMSBlockCommand::class)
            ->hasInstallCommand(function (InstallCommand $installCommand) {
                $installCommand->publishMigrations();
                $installCommand->publishConfigFile();
                $installCommand->askToRunMigrations();
            });
    }

    public function boot()
    {
        $this->app->booted(function () {
            if(Schema::hasTable('pages') && Schema::hasTable('navigations')) {
                parent::boot();
            }
        });

        Livewire::component('jorn-boerema.bz-cms.livewire.page_template', AcceptInvitation::class);
    }
}
