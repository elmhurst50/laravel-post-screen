<?php

namespace samjoyce777\LaravelPostScreen;

use Illuminate\Support\ServiceProvider;
use Illuminate\Html\FormBuilder;

class ScreenServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/screen.php' => config_path('screen.php')
        ], 'config');
    }

   /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Screen', 'samjoyce777\LaravelPostScreen\Screen');

        $config = require(__DIR__.'/config/screen.php');

        config($config);
    }

}
