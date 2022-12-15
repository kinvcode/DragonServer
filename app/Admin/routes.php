<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('base-address', 'BaseAddressController');
    $router->resource('roles', 'DnfRoleController')->except(['edit', 'update', 'destroy']);
    $router->get('role/master', 'DnfRoleController@masterJob');
    $router->match(['POST','PUT'],'role/master', 'DnfRoleController@saveMasterJob');
    $router->get('options/job', 'DnfRoleController@jobOptions');
    $router->resource('town-coordinates', 'TownCoordinateController');
    $router->resource('dungeons', 'DungeonController');
    $router->resource('jobs', 'JobController');
    $router->resource('job/strategies', 'JobStrategyController');
    $router->resource('accounts', 'DnfAccountController');

    $router->get('strategy/dungeon', 'JobStrategyController@dungeon');
    $router->post('strategy/dungeon', 'JobStrategyController@saveDungeon');
    $router->get('map-options', 'JobStrategyController@mapOptions');
    $router->get('role/{role_id}/job','RoleJobController@editJob');
    $router->put('role/{role_id}/job','RoleJobController@updateJob');
});
