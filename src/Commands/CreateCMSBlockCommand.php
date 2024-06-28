<?php

namespace JornBoerema\BzCMS\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateCMSBlockCommand extends Command
{
    protected $signature = 'bz-cms:create-block {name}';
    protected $description = 'Create a new CMS block';

    public function handle()
    {
        $name = $this->argument('name');
        $className = Str::studly($name);
        $viewName = Str::snake($name);

        $this->createClassFile($className, $viewName);
        $this->createViewFile($viewName);

        $this->info("CMS block '{$name}' created successfully!");
        $this->line('');
        $this->info("Don't forget to add '\App\CMSBlocks\{$className}::class' to the block array in the bz-cms.php config.");
    }

    protected function createClassFile($className, $viewName)
    {
        $directory = app_path('CMSBlocks');
        $filePath = $directory . '/' . $className . '.php';

        // Ensure the directory exists
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
            $this->info("Directory '$directory' created.");
        }

        // Check if the file already exists
        if (File::exists($filePath)) {
            $this->error("CMS block class '{$className}' already exists.");
            return;
        }

        // Get the content from the stub file and replace placeholder
        $stubPath = __DIR__ . '/../../stubs/cms-block.stub';
        $content = File::get($stubPath);
        $content = str_replace('{{ className }}', $className, $content);
        $content = str_replace('{{ viewName }}', $viewName, $content);

        // Create the file with the content from the stub
        File::put($filePath, $content);
        $this->info("CMS block class '{$className}' created at '{$filePath}'.");
    }

    protected function createViewFile($viewName)
    {
        $directory = resource_path('views/cms-blocks');
        $filePath = $directory . '/' . $viewName . '.blade.php';

        // Ensure the directory exists
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
            $this->info("Directory '$directory' created.");
        }

        // Check if the file already exists
        if (File::exists($filePath)) {
            $this->error("CMS block view '{$viewName}' already exists.");
            return;
        }

        // Get the content from the view stub file
        $stubPath = __DIR__ . '/../../stubs/cms-block-view.stub';
        $content = File::get($stubPath);

        // Create the file with the content from the stub
        File::put($filePath, $content);
        $this->info("CMS block view '{$viewName}' created at '{$filePath}'.");
    }
}
