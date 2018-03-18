<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);

        Relation::morphMap([
            'school' => 'App\Models\SchoolAdmin',
            'student' => 'App\Models\Student',
            'parent' => 'App\Models\Parents',
            'driver' => 'App\Models\Driver',
            'teacher' => 'App\Models\Teacher',
            'admin' => 'App\Models\SchoolAdmin',
        ]);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
