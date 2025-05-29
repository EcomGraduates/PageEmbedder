<?php

// Settings routes (admin only)
Route::group(['middleware' => ['web', 'auth', 'roles'], 'roles' => ['admin'], 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\PageEmbedder\Http\Controllers'], function () {
    // Page Embedder settings page
    Route::get('/app-settings/page-embedder', ['uses' => 'SettingsController@index'])->name('pageembedder.settings');
    Route::post('/app-settings/page-embedder', ['uses' => 'SettingsController@save'])->name('pageembedder.settings.save');
    
    // Separate routes for embedded pages and navbar links
    Route::post('/app-settings/page-embedder/embedded', ['uses' => 'SettingsController@saveEmbedded'])->name('pageembedder.settings.save.embedded');
    Route::post('/app-settings/page-embedder/navbar', ['uses' => 'SettingsController@saveNavbar'])->name('pageembedder.settings.save.navbar');
    Route::delete('/app-settings/page-embedder/embedded', ['uses' => 'SettingsController@deleteEmbedded'])->name('pageembedder.settings.delete.embedded');
    Route::delete('/app-settings/page-embedder/navbar', ['uses' => 'SettingsController@deleteNavbar'])->name('pageembedder.settings.delete.navbar');
});

// Embedded page routes (accessible to all authenticated users)
Route::group(['middleware' => ['web', 'auth'], 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\PageEmbedder\Http\Controllers'], function () {
    // Dynamic route for embedded pages
    Route::get('/embedded/{path}', ['uses' => 'EmbeddedController@show'])
        ->where('path', '.*')
        ->name('pageembedder.show');
}); 