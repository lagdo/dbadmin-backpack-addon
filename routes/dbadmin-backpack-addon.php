<?php

use Lagdo\DbAdmin\Db\DbAdminPackage;

/*
|--------------------------------------------------------------------------
| Lagdo\Dbadmin\Backpack Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Lagdo\Dbadmin\Backpack package.
|
*/

/**
 * User Routes
 */

// Route::group([
//     'middleware'=> array_merge(
//     	(array) config('backpack.base.web_middleware', 'web'),
//     ),
// ], function() {
//     Route::get('something/action', \Lagdo\Dbadmin\Backpack\Http\Controllers\SomethingController::actionName());
// });


/**
 * Admin Routes
 */

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
], function () {
    Route::get('/dbadmin', fn() =>
            view('lagdo.dbadmin-backpack-addon::dbadmin'))
        ->middleware(['jaxon.dbadmin.config'])
        ->name('dbadmin');
    Route::post('/dbadmin/jaxon', fn() => response()->json([]))
        ->middleware(['jaxon.dbadmin.config', 'jaxon.ajax'])
        ->name('dbadmin.jaxon');
    Route::get('/export/{filename}', function(string $filename) {
        $reader = jaxon()->package(DbAdminPackage::class)->getOption('export.reader');
        $content = !is_callable($reader) ? "No export reader set." : $reader($filename);
        return response($content)->header('Content-Type', 'text/plain');
    })->middleware(['auth', 'jaxon.dbadmin.config'])
        ->name('dbadmin.export');
    Route::get('/dbaudit', fn() =>
            view('lagdo.dbadmin-backpack-addon::dbaudit'))
        ->middleware(['jaxon.dbaudit.config'])
        ->name('dbaudit');
    Route::post('/dbaudit/jaxon', fn() => response()->json([]))
        ->middleware(['jaxon.dbaudit.config', 'jaxon.ajax'])
        ->name('dbaudit.jaxon');
});
