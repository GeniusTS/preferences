<?php

namespace GeniusTS\Preferences;


use Illuminate\Support\ServiceProvider;

/**
 * Class PreferencesServiceProvider
 *
 * @package GeniusTS\Core
 */
class PreferencesServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->alias(PreferencesManager::class, 'Preferences');

        $this->app->singleton(PreferencesManager::class, function ()
        {
            return new PreferencesManager();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        View::composer('geniusts_preferences::settings', function ($view)
        {
            return $view->with('$preferences', $this->app['preferences']);
        });
    }
}
