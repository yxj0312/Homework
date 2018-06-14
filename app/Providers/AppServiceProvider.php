<?php

namespace App\Providers;

// use App\Channel;
use Illuminate\Support\ServiceProvider;

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
        // \View::composer('*', function ($view) {
        //     $channels = \Cache::rememberForever('channels', function () {
        //         return Channel::all();
        //     });

        //     $view->with('channels', $channels);
        // });

        //equal to above, but it gonna run before 'RefreshDatabase' by test.
        // \View::share('channels', Channel::all());

        \Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
