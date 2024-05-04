<?php

namespace WPN;

use Throwable;
use WPN\Exceptions\WPNInitializationException;

final class App
{
    public string $template_path = '';
    public string $asset_path = '';

    /**
     * @var array<string, mixed>
     */
    protected array $config;

    /**
     * @throws WPNInitializationException
     */
    public function init(string $config_path = ''): self
    {
        $config_path = $config_path ?? get_template_directory().'/inc/config.php';

        add_theme_support('post-thumbnails');

        if ( ! file_exists($config_path)) {
            throw new WPNInitializationException('Unable to find config file');
        }

        try {
            $this->config = require $config_path;
        } catch (Throwable $e) {
            trigger_error("Config file not found at: $config_path", E_USER_WARNING);
            $this->config = [];
        }

        $this->template_path = $this->config['template_path'] ?? 'template-parts';
        $this->asset_path    = $this->config['asset_path'] ?? 'assets';

        if (array_key_exists('disable_file_editing', $this->config) && $this->config['disable_file_editing']) {
            $this->disableFileEditing();
        }

        add_filter('wpn_app', fn() => $this);

        return $this->registerPlugins()
                    ->registerFeatures()
                    ->registerProviders();
    }

    public function disableFileEditing(): self
    {
        add_action('init', function () {
            define('DISALLOW_FILE_EDIT', true);
        });

        return $this;
    }

    public function assetPath(): string
    {
        return get_stylesheet_directory_uri().'/'.$this->asset_path;
    }

    public function templatePartDirectory(): string
    {
        return $this->template_path;
    }

    public static function environment(string $environment): bool
    {
        if ($environment == 'local') {
            return WP_DEBUG || str_contains(get_site_url(), 'localhost');
        }

        if ($environment == 'production') {
            return ! WP_DEBUG || ! str_contains(get_site_url(), 'localhost');
        }

        return false;
    }

    private function registerProviders(): App
    {
        foreach ($this->config['providers'] ?? [] as $provider) {
            (new $provider());
        }

        return $this;
    }

    private function registerPlugins(): App
    {
        foreach ($this->config['plugins'] ?? [] as $plugin => $settings) {
            $registered_plugin = is_string($plugin) ? $plugin : $settings;
            (new $registered_plugin)->register($this->config['plugins'][$registered_plugin] ?? []);
        }

        return $this;
    }

    private function registerFeatures(): App
    {
        foreach ($this->config['features'] ?? [] as $feature) {
            (new $feature)->register();
        }

        return $this;
    }
}