<?php

declare (strict_types=1);
namespace Laravel\Ui\Presets;

use Illuminate\Filesystem\Filesystem;
class Bootstrap extends Preset
{
    /**
     * Install the preset.
     */
    public static function install(): void
    {
        static::update_packages();
        static::update_vite_configuration();
        static::update_sass();
        static::update_bootstrapping();
        static::remove_node_modules();
    }
    /**
     * Update the given package array.
     *
     * @return array
     */
    protected static function update_package_array(array $packages)
    {
        return ['bootstrap' => '^5.2.3', '@popperjs/core' => '^2.11.6', 'sass' => '^1.56.1'] + $packages;
    }
    /**
     * Update the Vite configuration.
     *
     * @return void
     */
    protected static function update_vite_configuration()
    {
        copy(__DIR__ . '/bootstrap-stubs/vite.config.js', base_path('vite.config.js'));
    }
    /**
     * Update the Sass files for the application.
     *
     * @return void
     */
    protected static function update_sass()
    {
        (new Filesystem())->ensure_directory_exists(resource_path('sass'));
        copy(__DIR__ . '/bootstrap-stubs/_variables.scss', resource_path('sass/_variables.scss'));
        copy(__DIR__ . '/bootstrap-stubs/app.scss', resource_path('sass/app.scss'));
    }
    /**
     * Update the bootstrapping files.
     *
     * @return void
     */
    protected static function update_bootstrapping()
    {
        copy(__DIR__ . '/bootstrap-stubs/bootstrap.js', resource_path('js/bootstrap.js'));
    }
}