<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if(config('app.forceScheme')!=null) {
            URL::forceScheme(config('app.forceScheme'));
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // DB::listen(
        //     function ($sql) {
        //         echo $sql->sql;
        //         echo "\n";
        //     }
        // );
    }
}
