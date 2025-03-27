<?php

// Settings routes (admin only)
Route::group(['middleware' => ['web', 'auth', 'roles'], 'roles' => ['admin'], 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\HelloWorld\Http\Controllers'], function () {
    // Hello World settings page
    Route::get('/app-settings/hello-world', ['uses' => 'SettingsController@index'])->name('helloworld.settings');
}); 