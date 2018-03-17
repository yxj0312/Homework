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
        
        //This Query will be toggled until view loaded.
        \View::composer('*', function($view){
            $view->with('channels',Channel::all());
        });

        //equal to above, but it gonna run before 'RefreshDatabase' by test.
        // \View::share('channels', Channel::all());

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
