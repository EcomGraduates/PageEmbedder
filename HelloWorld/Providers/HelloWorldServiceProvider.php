<?php

namespace Modules\HelloWorld\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class HelloWorldServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(Router $router)
    {
        $this->registerViews();
        $this->hooks();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Additional registration logic if needed
    }

    /**
     * Register views.
     */
    protected function registerViews()
    {
        $viewPath = resource_path('views/modules/helloworld');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/helloworld';
        }, \Config::get('view.paths')), [$sourcePath]), 'helloworld');
    }

    /**
     * Module hooks.
     */
    public function hooks()
    {
        // Add Hello World to settings menu
        \Eventy::addFilter('settings.sections', function ($sections) {
            $sections['hello-world'] = [
                'title' => __('Hello World'),
                'icon' => 'info-circle', // Using an appropriate icon
                'order' => 150
            ];
            return $sections;
        }, 15);

        // Set the view to load for settings
        \Eventy::addFilter('settings.view', function ($view, $section) {
            if ($section !== 'hello-world') {
                return $view;
            }
            return 'helloworld::settings';
        }, 20, 2);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides()
    {
        return [];
    }
} 