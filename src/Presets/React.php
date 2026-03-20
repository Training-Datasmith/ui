<?php

declare (strict_types=1);
namespace Laravel\Ui\Presets;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
class React extends Preset
{
    /**
     * Install the preset.
     */
    public static function install(): void
    {
        static::ensure_component_directory_exists();
        static::update_packages();
        static::update_vite_configuration();
        static::update_bootstrapping();
        static::update_component();
        static::add_vite_react_refresh_directive();
        static::remove_node_modules();
    }
    /**
     * Update the given package array.
     *
     * @return array
     */
    protected static function update_package_array(array $packages)
    {
        return ['@vitejs/plugin-react' => '^4.2.0', 'react' => '^18.2.0', 'react-dom' => '^18.2.0'] + Arr::except($packages, ['@vitejs/plugin-vue', 'vue']);
    }
    /**
     * Update the Vite configuration.
     *
     * @return void
     */
    protected static function update_vite_configuration()
    {
        copy(__DIR__ . '/react-stubs/vite.config.js', base_path('vite.config.js'));
    }
    /**
     * Update the example component.
     *
     * @return void
     */
    protected static function update_component()
    {
        (new Filesystem())->delete(resource_path('js/components/ExampleComponent.vue'));
        copy(__DIR__ . '/react-stubs/Example.jsx', resource_path('js/components/Example.jsx'));
    }
    /**
     * Update the bootstrapping files.
     *
     * @return void
     */
    protected static function update_bootstrapping()
    {
        copy(__DIR__ . '/react-stubs/app.js', resource_path('js/app.js'));
    }
    /**
     * Add Vite's React Refresh Runtime
     *
     * @return void
     */
    protected static function add_vite_react_refresh_directive()
    {
        $view = static::get_view_path('layouts/app.blade.php');
        if (!file_exists($view)) {
            return;
        }
        file_put_contents($view, str_replace('@vite(', '@viteReactRefresh' . PHP_EOL . '    @vite(', file_get_contents($view)));
    }
    /**
     * Get full view path relative to the application's configured view path.
     *
     * @param  string  $path
     */
    protected static function get_view_path($path): string
    {
        return implode(DIRECTORY_SEPARATOR, [config('view.paths')[0] ?? resource_path('views'), $path]);
    }
}