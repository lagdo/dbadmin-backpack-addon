<?php

/*
|--------------------------------------------------------------------------
| Lagdo\Backpack\Dbadmin Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Lagdo\Backpack\Dbadmin package.
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
//     Route::get('something/action', \Lagdo\Backpack\Dbadmin\Http\Controllers\SomethingController::actionName());
// });


/**
 * Admin Routes
 */

// Route::group([
//     'prefix' => config('backpack.base.route_prefix', 'admin'),
//     'middleware' => array_merge(
//         (array) config('backpack.base.web_middleware', 'web'),
//         (array) config('backpack.base.middleware_key', 'admin')
//     ),
// ], function () {
//     Route::crud('some-entity-name', \Lagdo\Backpack\Dbadmin\Http\Controllers\Admin\EntityNameCrudController::class);
// });