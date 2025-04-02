<?php

/*
|--------------------------------------------------------------------------
| Register Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the module.
| It's loaded automatically by the framework.
|
*/

if (!app()->routesAreCached()) {
    require __DIR__ . '/Http/routes.php';
}

// Ensure we have a default empty array for embedded pages
if (is_null(\App\Option::get('pageembedder_pages'))) {
    \App\Option::set('pageembedder_pages', '[]');
} 