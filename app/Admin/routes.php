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

    // 基址管理
    $router->resource('base-address', 'BaseAddressController');
    // 七度基址表单
    $router->get('address/qidu', 'BaseAddressController@qiduForm');
    $router->post('address/qidu', 'BaseAddressController@updateQidu');
    // 心悦基址表单
    $router->get('address/xinyue', 'BaseAddressController@xinyueForm');
    $router->post('address/xinyue', 'BaseAddressController@updateXinyue');

    // 城镇列表
    $router->resource('town-coordinates', 'TownCoordinateController');

    // 副本列表
    $router->resource('dungeons', 'DungeonController');

    // 账号管理
    $router->resource('accounts', 'DnfAccountController');
    // 账号角色
    $router->get('account/{qq}','DnfAccountController@accounts');

    // 角色管理
    $router->resource('roles', 'DnfRoleController')->except(['edit', 'update', 'destroy']);

    // 策略管理
    $router->resource('job/strategies', 'JobStrategyController');

    // 刷图策略
    $router->get('strategy/dungeon', 'JobStrategyController@dungeon');
    $router->post('strategy/dungeon', 'JobStrategyController@saveDungeon');

    // 剧情策略
    $router->get('strategy/mainline', 'JobStrategyController@mainline');
    $router->post('strategy/mainline', 'JobStrategyController@saveMainline');

    // 每日任务策略
    $router->get('strategy/daily','JobStrategyController@daily');
    $router->post('strategy/daily','JobStrategyController@saveDaily');

    // 角色任务
    $router->get('role/{role_id}/job', 'RoleJobController@editJob');
    $router->put('role/{role_id}/job', 'RoleJobController@updateJob');

    // 策略选项列表
    $router->get('options/job', 'DnfRoleController@jobOptions');
    // 地图选项
    $router->get('map-options', 'JobStrategyController@mapOptions');

    // 全局策略（暂时不可用）
    $router->get('role/master', 'DnfRoleController@masterJob');
    $router->match(['POST', 'PUT'], 'role/master', 'DnfRoleController@saveMasterJob');
    // 暂时无用
    $router->resource('jobs', 'JobController');
});
