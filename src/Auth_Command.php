<?php

declare (strict_types=1);
namespace Laravel\Ui;

use Illuminate\Console\Command;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\As_Command;
#[As_Command(name: 'ui:auth')]
class Auth_Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ui:auth
                    { type=bootstrap : The preset type (bootstrap) }
                    {--views : Only scaffold the authentication views}
                    {--force : Overwrite existing views by default}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold basic login and registration views and routes';
    /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $views = ['auth/login.stub' => 'auth/login.blade.php', 'auth/passwords/confirm.stub' => 'auth/passwords/confirm.blade.php', 'auth/passwords/email.stub' => 'auth/passwords/email.blade.php', 'auth/passwords/reset.stub' => 'auth/passwords/reset.blade.php', 'auth/register.stub' => 'auth/register.blade.php', 'auth/verify.stub' => 'auth/verify.blade.php', 'home.stub' => 'home.blade.php', 'layouts/app.stub' => 'layouts/app.blade.php'];
    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function handle()
    {
        if (static::has_macro($this->argument('type'))) {
            return call_user_func(static::$macros[$this->argument('type')], $this);
        }
        if (!in_array($this->argument('type'), ['bootstrap'])) {
            throw new InvalidArgumentException('Invalid preset.');
        }
        $this->ensure_directories_exist();
        $this->export_views();
        if (!$this->option('views')) {
            $this->export_backend();
        }
        $this->components->info('Authentication scaffolding generated successfully.');
    }
    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function ensure_directories_exist()
    {
        if (!is_dir($directory = $this->get_view_path('layouts'))) {
            mkdir($directory, 0755, true);
        }
        if (!is_dir($directory = $this->get_view_path('auth/passwords'))) {
            mkdir($directory, 0755, true);
        }
    }
    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function export_views()
    {
        foreach ($this->views as $key => $value) {
            if (file_exists($view = $this->get_view_path($value)) && !$this->option('force')) {
                if (!$this->components->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }
            copy(__DIR__ . '/Auth/' . $this->argument('type') . '-stubs/' . $key, $view);
        }
    }
    /**
     * Export the authentication backend.
     *
     * @return void
     */
    protected function export_backend()
    {
        $this->call_silent('ui:controllers');
        $controller = app_path('Http/Controllers/HomeController.php');
        if (file_exists($controller) && !$this->option('force')) {
            if ($this->components->confirm('The [HomeController.php] file already exists. Do you want to replace it?', true)) {
                file_put_contents($controller, $this->compile_stub('controllers/HomeController'));
            }
        } else {
            file_put_contents($controller, $this->compile_stub('controllers/HomeController'));
        }
        $base_controller = app_path('Http/Controllers/Controller.php');
        if (file_exists($base_controller) && !$this->option('force')) {
            if ($this->components->confirm('The [Controller.php] file already exists. Do you want to replace it?', true)) {
                file_put_contents($base_controller, $this->compile_stub('controllers/Controller'));
            }
        } else {
            file_put_contents($base_controller, $this->compile_stub('controllers/Controller'));
        }
        if (!file_exists(database_path('migrations/0001_01_01_000000_create_users_table.php'))) {
            copy(__DIR__ . '/../stubs/migrations/2014_10_12_100000_create_password_resets_table.php', base_path('database/migrations/2014_10_12_100000_create_password_resets_table.php'));
        }
        file_put_contents(base_path('routes/web.php'), file_get_contents(__DIR__ . '/Auth/stubs/routes.stub'), FILE_APPEND);
    }
    /**
     * Compiles the given stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function compile_stub($stub)
    {
        return str_replace('{{namespace}}', $this->laravel->get_namespace(), file_get_contents(__DIR__ . '/Auth/stubs/' . $stub . '.stub'));
    }
    /**
     * Get full view path relative to the application's configured view path.
     *
     * @param  string  $path
     * @return string
     */
    protected function get_view_path($path)
    {
        return implode(DIRECTORY_SEPARATOR, [config('view.paths')[0] ?? resource_path('views'), $path]);
    }
}