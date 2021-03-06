<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.as'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('databasetables', DatabaseTablesController::class);
    $router->resource('fields', FieldController::class);

    $router->get('/fieldmanage/{id}', [\App\Admin\Controllers\FieldmanageController::class, 'fieldmanage']);

});
