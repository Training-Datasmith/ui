<?php

declare (strict_types=1);
namespace Laravel\Ui\Presets;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
class Vue extends Preset
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
        static::remove_node_modules();
    }
    /**
     * Update the given package array.
     *
     * @return array
     */
    protected static function update_package_array(array $packages)
    {
        return ['@vitejs/plugin-vue' => '^5.2.1', 'vue' => '^3.5.13'] + Arr::except($packages, ['@vitejs/plugin-react', 'react', 'react-dom']);
    }
    /**
     * Update the Vite configuration.
     *
     * @return void
     */
    protected static function update_vite_configuration()
    {
        copy(__DIR__ . '/vue-stubs/vite.config.js', base_path('vite.config.js'));
    }
    /**
     * Update the example component.
     *
     * @return void
     */
    protected static function update_component()
    {
        (new Filesystem())->delete(resource_path('js/components/Example.js'));
        copy(__DIR__ . '/vue-stubs/ExampleComponent.vue', resource_path('js/components/ExampleComponent.vue'));
    }
    /**
     * Update the bootstrapping files.
     *
     * @return void
     */
    protected static function update_bootstrapping()
    {
        copy(__DIR__ . '/vue-stubs/app.js', resource_path('js/app.js'));
    }
}