## Installation

Your can install the package via composer:

```bash
composer require jornboerema/bz-cms
```

Install the plugin with:

```bash
php artisan bz-cms:install
```

Migrate the database with:

```bash
php artisan migrate
```

## Usage

Register the plugin in your AdminPanelProvider.

```php
use Filament\Panel;
use JornBoerema\BzCMS\BzCMSPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            BzCMSPlugin::make(),
        ]);
}
```

You can create a new page block with:

```bash
php artisan bz-cms:create-block {name}
```

If you don't already have a livewire layout, create it with:
```bash
php artisan livewire:layout
```

Replace the content of the livewire layout file with:

```bladehtml
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @stack('head')

        <title>{{ config('app.name') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body>
        {{ $slot }}
    </body>
</html>
```
