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