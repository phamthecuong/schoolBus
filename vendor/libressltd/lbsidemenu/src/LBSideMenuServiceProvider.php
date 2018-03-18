<?php

namespace LIBRESSLtd\LBSideMenu;

use Illuminate\Support\ServiceProvider;

class LBSideMenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadViewsFrom(__DIR__.'/views/role', 'role');
        // $this->loadViewsFrom(__DIR__.'/views', 'dp');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/libressltd/lbsidemenu'),
            __DIR__.'/migrations' => base_path('database/migrations'),
            __DIR__.'/models' => base_path('app/Models'),
        //     __DIR__.'/requests' => base_path('app/Http/Requests/DeepPermission'),
        //     __DIR__.'/middlewares' => base_path('app/Http/Middleware'),
        //     __DIR__.'/lang/en/deeppermission.php' => base_path('resources/lang/en/deeppermission.php'),
        ], 'lbsidemenu');
        // $this->loadViewsFrom(__DIR__.'/views', 'dp');
        // $this->publishes([

        //     __DIR__.'/config' => base_path('config'),
        // ], 'deeppermission_config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('LIBRESSLtd\LBSideMenu\Controllers\LBSM_itemController');
        // $this->app->make('LIBRESSLtd\DeepPermission\Controllers\PermissionGroupController');
        // $this->app->make('LIBRESSLtd\DeepPermission\Controllers\PermissionController');
        // $this->app->make('LIBRESSLtd\DeepPermission\Controllers\UserRoleController');
        // $this->app->make('LIBRESSLtd\DeepPermission\Controllers\RolePermissionController');
        // $this->app->make('LIBRESSLtd\DeepPermission\Controllers\UserPermissionController');
        // $this->app->make('LIBRESSLtd\DeepPermission\Controllers\SettingController');
        // $this->app->make('LIBRESSLtd\DeepPermission\Traits\DPUserModelTrait');
    }
}
