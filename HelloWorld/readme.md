# FreeScout Module Development Guide

This documentation provides a comprehensive guide to developing modules for FreeScout, covering both basic module creation and advanced features like API endpoints, custom URL templates, and interactive testing interfaces.

## Table of Contents

1. [Introduction](#introduction)
2. [Basic Module Development](#basic-module-development)
   - [Module Structure](#module-structure)
   - [Module Configuration](#module-configuration)
   - [Service Provider](#service-provider)
   - [Settings Pages](#settings-pages)
   - [Routes and Controllers](#routes-and-controllers)
3. [Advanced Features](#advanced-features)
   - [API Development](#api-development)
   - [Custom URL Templates](#custom-url-templates)
   - [JavaScript Integration](#javascript-integration)
   - [Interactive Testing](#interactive-testing)
4. [Best Practices](#best-practices)
   - [Security Considerations](#security-considerations)
   - [Performance Optimization](#performance-optimization)
   - [Deployment Process](#deployment-process)
5. [Troubleshooting](#troubleshooting)

## Introduction

FreeScout is an open-source helpdesk and shared inbox system based on Laravel. Modules allow you to extend FreeScout's core functionality. This guide covers everything from creating a simple "Hello World" module to implementing advanced features like REST APIs and custom integrations.

## Basic Module Development

### Module Structure

A typical FreeScout module follows this directory structure:

```
YourModule/
├── Config/                # Configuration files
├── Database/
│   └── Migrations/        # Database migrations
├── Http/
│   ├── Controllers/       # Request handlers
│   ├── Middleware/        # Custom middleware
│   └── routes.php         # Route definitions
├── Providers/
│   └── ServiceProvider.php # Module registration
├── Resources/
│   ├── views/             # Blade templates
│   │   └── settings.blade.php
│   └── lang/              # Translations
├── Public/                # Assets (JS, CSS, images)
├── module.json            # Module metadata
├── composer.json          # Dependencies
└── start.php              # Entry point
```

### Module Configuration

Every FreeScout module needs a `module.json` file to define its metadata:

```json
{
  "name": "Hello World",
  "alias": "helloworld",
  "description": "A simple demonstration module for FreeScout",
  "version": "1.0.0",
  "author": "Your Name",
  "authorUrl": "https://yourwebsite.com",
  "requiredAppVersion": "1.8.7",
  "license": "MIT",
  "keywords": ["demo", "hello", "world"],
  "img": "/modules/helloworld/images/module.svg",
  "active": 0,
  "order": 0,
  "providers": [
    "Modules\\HelloWorld\\Providers\\HelloWorldServiceProvider"
  ],
  "aliases": {},
  "files": [
    "start.php"
  ],
  "requires": []
}
```

Key points:
- `alias`: Used in your code and URLs - keep it lowercase and simple
- `providers`: List of service providers (fully qualified namespaces)
- `requiredAppVersion`: Minimum FreeScout version required
- `img`: Path to module icon (appears in module list)

### Service Provider

The service provider is the heart of your module. It registers routes, views, and hooks into FreeScout:

```php
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
        $this->registerMiddleware($router);
        $this->hooks();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Additional registration logic
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
     * Register middleware if needed.
     */
    protected function registerMiddleware(Router $router)
    {
        // Register custom middleware if necessary
    }

    /**
     * Module hooks.
     */
    public function hooks()
    {
        // Register settings menu item
        \Eventy::addFilter('settings.sections', function ($sections) {
            $sections['hello-world'] = [
                'title' => __('Hello World'),
                'icon' => 'info-circle',
                'order' => 150
            ];
            return $sections;
        }, 15);

        // Tell FreeScout which view to load for settings
        \Eventy::addFilter('settings.view', function ($view, $section) {
            if ($section !== 'hello-world') {
                return $view;
            }
            return 'helloworld::settings';
        }, 20, 2);
    }
}
```

### Settings Pages

To add a settings page to your module:

1. **Create a route in `Http/routes.php`**:

```php
<?php

// Settings routes (admin only)
Route::group(['middleware' => ['web', 'auth', 'roles'], 'roles' => ['admin'], 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\HelloWorld\Http\Controllers'], function () {
    // IMPORTANT: Must use app-settings prefix for settings
    Route::get('/app-settings/hello-world', ['uses' => 'SettingsController@index'])->name('helloworld.settings');
    Route::post('/app-settings/hello-world', ['uses' => 'SettingsController@save'])->name('helloworld.settings.save');
});
```

2. **Create a controller in `Http/Controllers/SettingsController.php`**:

```php
<?php

namespace Modules\HelloWorld\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:admin');
    }
    
    public function index()
    {
        // Get saved settings from database
        $message = Option::get('helloworld_message', 'Hello World!');
        
        return view('helloworld::settings', [
            'message' => $message,
        ]);
    }
    
    public function save(Request $request)
    {
        // IMPORTANT: Use $request->validate, not $this->validate
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
        
        // Save to database
        Option::set('helloworld_message', $request->message);
        
        \Session::flash('flash_success_floating', __('Settings saved'));
        
        return redirect()->route('helloworld.settings');
    }
}
```

3. **Create a view in `Resources/views/settings.blade.php`**:

```blade
@extends('layouts.app')

@section('title', __('Hello World Settings'))

@section('content')
<div class="section-heading">
    {{ __('Hello World Settings') }}
</div>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ __('General Settings') }}</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('helloworld.settings.save') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                            <label for="message" class="col-sm-2 control-label">{{ __('Message') }}</label>

                            <div class="col-sm-6">
                                <input id="message" type="text" class="form-control" name="message" value="{{ old('message', $message) }}" required autofocus>

                                @if ($errors->has('message'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                                <p class="help-block">
                                    {{ __('This message will be displayed on the module page.') }}
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6 col-sm-offset-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

4. **Create `start.php` to load your routes**:

```php
<?php

if (!app()->routesAreCached()) {
    require __DIR__ . '/Http/routes.php';
}
```

## Advanced Features

### API Development

#### 1. Token-Based Authentication

First, create a middleware for API authentication:

```php
<?php

namespace Modules\HelloWorld\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Option;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->input('token');
        $valid_token = Option::get('helloworld_api_token');
        
        if (empty($token) || $token !== $valid_token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $next($request);
    }
}
```

Register the middleware in your service provider:

```php
protected function registerMiddleware(Router $router)
{
    $router->aliasMiddleware('helloworld.api.token', \Modules\HelloWorld\Http\Middleware\ApiTokenMiddleware::class);
}
```

#### 2. API Routes

Add API routes to your `routes.php`:

```php
// API routes (protected by token middleware)
Route::group(['middleware' => ['helloworld.api.token'], 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\HelloWorld\Http\Controllers'], function () {
    Route::get('/api/helloworld/message', ['uses' => 'ApiController@getMessage'])->name('helloworld.api.message');
});
```

#### 3. API Controller

Create `Http/Controllers/ApiController.php`:

```php
<?php

namespace Modules\HelloWorld\Http\Controllers;

use App\Option;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    /**
     * Get the configured message.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessage()
    {
        try {
            $message = Option::get('helloworld_message', 'Hello World!');
            
            return Response::json([
                'status' => 200,
                'data' => [
                    'message' => $message
                ]
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

#### 4. Add API Token Setting

Update your settings page to include an API token field:

```php
// In SettingsController.php
public function index()
{
    $message = Option::get('helloworld_message', 'Hello World!');
    $api_token = Option::get('helloworld_api_token');
    
    return view('helloworld::settings', [
        'message' => $message,
        'api_token' => $api_token,
    ]);
}

public function save(Request $request)
{
    $validated = $request->validate([
        'message' => 'required|string|max:255',
        'api_token' => 'nullable|string|min:16|max:64',
    ]);
    
    Option::set('helloworld_message', $request->message);
    
    if ($request->filled('api_token')) {
        Option::set('helloworld_api_token', $request->api_token);
    }
    
    \Session::flash('flash_success_floating', __('Settings saved'));
    
    return redirect()->route('helloworld.settings');
}
```

And update the settings view to add the token field:

```blade
<div class="form-group{{ $errors->has('api_token') ? ' has-error' : '' }}">
    <label for="api_token" class="col-sm-2 control-label">{{ __('API Token') }}</label>

    <div class="col-sm-6">
        <div class="input-group">
            <input id="api_token" type="text" class="form-control" name="api_token" value="{{ old('api_token', $api_token) }}" maxlength="64">
            <span class="input-group-btn">
                <button class="btn btn-info generate-token" type="button">
                    <i class="glyphicon glyphicon-refresh"></i> {{ __('Generate Token') }}
                </button>
            </span>
        </div>

        @if ($errors->has('api_token'))
            <span class="help-block">
                <strong>{{ $errors->first('api_token') }}</strong>
            </span>
        @endif
        <p class="help-block">
            {{ __('Required for API access. Keep secure!') }}
        </p>
    </div>
</div>

<!-- Add JS for token generation -->
<script type="text/javascript" {!! \Helper::cspNonceAttr() !!}>
    document.addEventListener('DOMContentLoaded', function() {
        const generateButton = document.querySelector('.generate-token');
        
        generateButton.addEventListener('click', function() {
            let token = '';
            const possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            
            for (let i = 0; i < 32; i++) {
                token += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            
            document.getElementById('api_token').value = token;
        });
    });
</script>
```

### Custom URL Templates

For modules that expose URLs in API responses, implement a customizable URL template system:

1. **Add a URL template field to settings**:

```php
// In SettingsController.php
public function index()
{
    $message = Option::get('helloworld_message', 'Hello World!');
    $api_token = Option::get('helloworld_api_token');
    $custom_url_template = Option::get('helloworld_custom_url');
    
    return view('helloworld::settings', [
        'message' => $message,
        'api_token' => $api_token,
        'custom_url_template' => $custom_url_template,
    ]);
}

public function save(Request $request)
{
    $validated = $request->validate([
        'message' => 'required|string|max:255',
        'api_token' => 'nullable|string|min:16|max:64',
        'custom_url_template' => 'nullable|string|max:255',
    ]);
    
    Option::set('helloworld_message', $request->message);
    
    if ($request->filled('api_token')) {
        Option::set('helloworld_api_token', $request->api_token);
    }
    
    Option::set('helloworld_custom_url', $request->custom_url_template);
    
    \Session::flash('flash_success_floating', __('Settings saved'));
    
    return redirect()->route('helloworld.settings');
}
```

2. **Create a URL builder method in your API controller**:

```php
/**
 * Build a custom URL for resources
 * 
 * @param string $resourceId
 * @return string
 */
private function buildResourceUrl($resourceId)
{
    $customUrlTemplate = \App\Option::get('helloworld_custom_url');
    
    if (!empty($customUrlTemplate)) {
        // Replace placeholder with actual value
        return str_replace('[id]', $resourceId, $customUrlTemplate);
    }
    
    // Default URL format
    return url('/helloworld/resource/'.$resourceId);
}
```

3. **Use this method in your API responses**:

```php
public function getResource($id)
{
    try {
        $resource = YourResource::find($id);
        
        if (!$resource) {
            return Response::json(['error' => 'Resource not found'], 404);
        }
        
        // Build resource URL using the template
        $resourceUrl = $this->buildResourceUrl($resource->id);
        
        return Response::json([
            'id' => $resource->id,
            'name' => $resource->name,
            'url' => $resourceUrl
        ], 200);
    } catch (\Exception $e) {
        return Response::json(['error' => $e->getMessage()], 500);
    }
}
```

### JavaScript Integration

When adding JavaScript to your module, follow these security best practices:

1. **Always use the Content Security Policy (CSP) nonce**:

```blade
<script type="text/javascript" {!! \Helper::cspNonceAttr() !!}>
    // Your JavaScript code
</script>
```

2. **Use vanilla JavaScript instead of jQuery when possible**:

```javascript
document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('my-button');
    
    button.addEventListener('click', function() {
        const result = document.getElementById('result');
        result.textContent = 'Button clicked!';
    });
});
```

3. **Register external JS files in your service provider**:

```php
\Eventy::addFilter('javascripts', function($javascripts) {
    $javascripts[] = \Module::getPublicPath('helloworld') . '/js/script.js';
    return $javascripts;
});
```

### Interactive Testing

Add an interactive API testing feature to your settings page:

```blade
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ __('API Testing') }}</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="test-endpoint">{{ __('Endpoint') }}</label>
                    <select id="test-endpoint" class="form-control">
                        <option value="message">{{ __('Get Message') }}</option>
                        <option value="resources">{{ __('List Resources') }}</option>
                    </select>
                </div>
                <button id="test-button" class="btn btn-success" {{ empty($api_token) ? 'disabled' : '' }}>
                    <i class="glyphicon glyphicon-play"></i> {{ __('Test API') }}
                </button>
                
                @if(empty($api_token))
                <p class="text-danger">
                    <i class="glyphicon glyphicon-warning-sign"></i> {{ __('Please set an API token first') }}
                </p>
                @endif
            </div>
            <div class="col-md-6">
                <pre id="test-result" style="min-height:150px;">{{ __('Results will appear here...') }}</pre>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" {!! \Helper::cspNonceAttr() !!}>
    document.addEventListener('DOMContentLoaded', function() {
        const testButton = document.getElementById('test-button');
        const resultElement = document.getElementById('test-result');
        
        testButton.addEventListener('click', function() {
            const endpoint = document.getElementById('test-endpoint').value;
            let url = '{{ url("/api/helloworld") }}/' + endpoint + '?token={{ $api_token }}';
            
            resultElement.textContent = 'Loading...';
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    resultElement.textContent = JSON.stringify(data, null, 2);
                })
                .catch(error => {
                    resultElement.textContent = 'Error: ' + error.message;
                });
        });
    });
</script>
```

## Best Practices

### Security Considerations

1. **Validate all user input**:
   ```php
   $validated = $request->validate([
       'field' => 'required|string|max:255',
   ]);
   ```

2. **Use CSP nonces for inline scripts**:
   ```blade
   <script {!! \Helper::cspNonceAttr() !!}>
   ```

3. **Always check permissions**:
   ```php
   if (!auth()->user()->isAdmin()) {
       return redirect()->route('dashboard')->with('error', __('Not enough permissions'));
   }
   ```

4. **Protect API endpoints with authentication**

5. **Escape output to prevent XSS**:
   ```blade
   {{ $safeVariable }}  <!-- Escaped -->
   {!! $rawHtml !!}     <!-- Unescaped - use with caution -->
   ```

### Performance Optimization

1. **Minimize database queries**
   - Use eager loading with `with()` for related models
   - Cache frequently accessed data

2. **Optimize JavaScript and CSS**
   - Minimize and combine assets for production
   - Load JavaScript at the end of the document

3. **Cache heavy computations**:
   ```php
   $value = \Cache::remember('cache-key', 60, function () {
       return expensiveOperation();
   });
   ```

### Deployment Process

1. **Create a release zip file**:
   ```bash
   zip -r YourModule-1.0.0.zip . -x "*.git*" "*node_modules*" "*.DS_Store"
   ```

2. **Test on a staging environment**

3. **Update version numbers** in both `module.json` and `composer.json`

4. **Document changes** in a changelog

## Troubleshooting

### Common Issues and Solutions

1. **"Page not found" after adding a settings menu item**
   - Ensure your route uses the `/app-settings/` prefix
   - Make sure the section name in the route matches the one in your service provider
   - Check for typos in route names and URLs

2. **JavaScript not working**
   - Add the CSP nonce attribute with `{!! \Helper::cspNonceAttr() !!}`
   - Check browser console for errors
   - Ensure scripts load after DOM is ready

3. **Module not appearing in modules list**
   - Verify `module.json` is properly formatted
   - Make sure namespace in `providers` matches your actual namespace
   - Check FreeScout logs for errors during module discovery

4. **Middleware issues**
   - Use `'middleware' => ['web', 'auth', 'roles'], 'roles' => ['admin']` format
   - In controllers, use `$this->middleware('roles:admin')` not `$this->middleware('admin')`

5. **Controller validation errors**
   - Use `$request->validate([])` instead of `$this->validate($request, [])`

6. **API authentication failures**
   - Ensure token is being passed correctly (usually as `?token=YOUR_TOKEN`)
   - Verify the token in the database matches the one being sent

When troubleshooting, always check Laravel logs at `storage/logs/laravel-*.log` for detailed error information.

---

This guide should help you create everything from simple modules to complex extensions with advanced features like APIs, custom URL templates, and interactive testing interfaces. For further assistance, refer to the [FreeScout documentation](https://github.com/freescout-helpdesk/freescout) or the [Laravel documentation](https://laravel.com/docs).
