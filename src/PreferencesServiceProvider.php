<?php

namespace GeniusTS\Preferences;


use Illuminate\Support\Facades\View;
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'geniusts_preferences');

        if (method_exists($this, 'loadMigrationsFrom'))
        {
            $this->loadMigrationsFrom(__DIR__ . '/../resources/migrations');
        }
        else
        {
            $this->publishes([
                __DIR__ . '/../resources/migrations' => database_path('migrations'),
            ], 'migrations');
        }

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/geniusts_preferences'),
        ], 'views');

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
            return $view->with('preferences', $this->app['preferences']);
        });
    }
}
