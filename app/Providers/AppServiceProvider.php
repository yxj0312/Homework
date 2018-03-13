<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Channel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * Here means, wenn a view is loaded, we always get a channel variable. 
     * 
     * Alternatively, you can create a new serviceprovider like ViewServiceProvider
     * 
     * @return void
     */
    public function boot()
    {
        // \View::composer(['threads.create','threads.index'], function ($view) {
        
        /* \View::composer('*', function($view){
            $view->with('channels',\App\Channel::all());
        }); */

        //equal to above
        \View::share('channels', Channel::all());

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
