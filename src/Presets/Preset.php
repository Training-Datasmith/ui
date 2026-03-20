<?php

declare (strict_types=1);
namespace Laravel\Ui\Presets;

use Illuminate\Filesystem\Filesystem;
class Preset
{
    /**
     * Ensure the component directories we need exist.
     *
     * @return void
     */
    protected static function ensure_component_directory_exists()
    {
        $filesystem = new Filesystem();
        if (!$filesystem->is_directory($directory = resource_path('js/components'))) {
            $filesystem->make_directory($directory, 0755, true);
        }
    }
    /**
     * Update the "package.json" file.
     *
     * @param  bool  $dev
     * @return void
     */
    protected static function update_packages($dev = true)
    {
        if (!file_exists(base_path('package.json'))) {
            return;
        }
        $configuration_key = $dev ? 'devDependencies' : 'dependencies';
        $packages = json_decode(file_get_contents(base_path('package.json')), true);
        $packages[$configuration_key] = static::update_package_array(array_key_exists($configuration_key, $packages) ? $packages[$configuration_key] : [], $configuration_key);
        ksort($packages[$configuration_key]);
        file_put_contents(base_path('package.json'), json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL);
    }
    /**
     * Remove the installed Node modules.
     *
     * @return void
     */
    protected static function remove_node_modules()
    {
        tap(new Filesystem(), function ($files): void {
            $files->delete_directory(base_path('node_modules'));
            $files->delete(base_path('yarn.lock'));
        });
    }
}