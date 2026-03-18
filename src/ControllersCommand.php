<?php

declare(strict_types=1);

namespace Laravel\Ui;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Finder\SplFileInfo;

#[AsCommand(name: 'ui:controllers')]
class ControllersCommand extends Command
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
        if (! is_dir($directory = app_path('Http/Controllers/Auth'))) {
            mkdir($directory, 0755, true);
        }

        $filesystem = new Filesystem();

        collect($filesystem->allFiles(__DIR__.'/../stubs/Auth'))
            ->each(function (SplFileInfo $file) use ($filesystem): void {
                $filesystem->copy(
                    $file->getPathname(),
                    app_path('Http/Controllers/Auth/'.Str::replaceLast('.stub', '.php', $file->getFilename()))
                );
            });

        $this->components->info('Authentication scaffolding generated successfully.');
    }
}
