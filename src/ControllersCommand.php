<?php

declare (strict_types=1);
namespace Laravel\Ui;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\As_Command;
use Symfony\Component\Finder\Spl_File_Info;
#[As_Command(name: 'ui:controllers')]
class Controllers_Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ui:controllers';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold the authentication controllers';
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (!is_dir($directory = app_path('Http/Controllers/Auth'))) {
            mkdir($directory, 0755, true);
        }
        $filesystem = new Filesystem();
        collect($filesystem->all_files(__DIR__ . '/../stubs/Auth'))->each(function (Spl_File_Info $file) use ($filesystem): void {
            $filesystem->copy($file->get_pathname(), app_path('Http/Controllers/Auth/' . Str::replace_last('.stub', '.php', $file->get_filename())));
        });
        $this->components->info('Authentication scaffolding generated successfully.');
    }
}