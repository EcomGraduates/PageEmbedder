<?php

namespace Modules\PageEmbedder\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Option;

define('PE_MODULE', 'pageembedder');

class PageEmbedderServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(Router $router)
    {
        $this->registerViews();
        $this->registerMiddleware($router);
        $this->registerPublicAssets();
        $this->hooks();
        $this->registerEmbeddedPages();
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
        $viewPath = resource_path('views/modules/pageembedder');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/pageembedder';
        }, \Config::get('view.paths')), [$sourcePath]), 'pageembedder');
    }

    /**
     * Register middleware.
     */
    protected function registerMiddleware(Router $router)
    {
        // No custom middleware needed for now
    }

    /**
     * Register public assets.
     */
    protected function registerPublicAssets()
    {
        $this->publishes([
            __DIR__.'/../Public' => public_path('modules/pageembedder/Public'),
        ], 'public');
        
        // Add module's CSS file to the application layout
        \Eventy::addFilter('stylesheets', function($styles) {
            $styles[] = \Module::getPublicPath(PE_MODULE).'/css/module.css';
            return $styles;
        });
    }

    /**
     * Module hooks.
     */
    public function hooks()
    {
        // Add Page Embedder to settings menu
        \Eventy::addFilter('settings.sections', function ($sections) {
            $sections['page-embedder'] = [
                'title' => __('Page Embedder'),
                'icon' => 'globe', // Using globe icon for external pages
                'order' => 150
            ];
            return $sections;
        }, 15);

        // Set the view to load for settings
        \Eventy::addFilter('settings.view', function ($view, $section) {
            if ($section !== 'page-embedder') {
                return $view;
            }
            return 'pageembedder::settings';
        }, 20, 2);
    }

    /**
     * Register embedded pages in the navigation
     */
    public function registerEmbeddedPages()
    {
        // Get saved embedded pages from database
        $embeddedPagesJson = Option::get('pageembedder_pages', '[]');
        $pages = json_decode($embeddedPagesJson, true);
        
        if (!empty($pages) && is_array($pages)) {
            // Add each page to the main menu
            \Eventy::addFilter('menu.main', function ($menuItems) use ($pages) {
                // Get current user
                $user = \Auth::user();
                $isAdmin = $user && $user->isAdmin();
                
                foreach ($pages as $page) {
                    if (!empty($page['title']) && !empty($page['path'])) {
                        // Skip admin-only pages if user is not an admin
                        if (!empty($page['admin_only']) && $page['admin_only'] && !$isAdmin) {
                            continue;
                        }
                        
                        // Only add to main menu if not set to display in navbar
                        if (empty($page['in_navbar'])) {
                            $iconClass = !empty($page['icon_class']) ? $page['icon_class'] : 'glyphicon-bookmark';
                            $menuItems[] = [
                                'title' => $page['title'],
                                'url' => url($page['path']),
                                'iconclass' => 'glyphicon ' . $iconClass,
                                'external' => false,
                            ];
                        }
                    }
                }
                return $menuItems;
            }, 20);
            
            // Add navbar items
            \Eventy::addAction('menu.append', function() use ($pages) {
                // Get current user
                $user = \Auth::user();
                $isAdmin = $user && $user->isAdmin();
                
                $navbarItems = [];
                
                foreach ($pages as $page) {
                    if (!empty($page['title']) && !empty($page['path']) && !empty($page['in_navbar'])) {
                        // Skip admin-only pages if user is not an admin
                        if (!empty($page['admin_only']) && $page['admin_only'] && !$isAdmin) {
                            continue;
                        }
                        
                        $navbarItems[] = $page;
                    }
                }
                
                if (!empty($navbarItems)) {
                    echo \View::make('pageembedder::partials.navbar_items', [
                        'pages' => $navbarItems
                    ])->render();
                }
            }, 20);
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides()
    {
        return [];
    }
} 