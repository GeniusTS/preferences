<?php

namespace GeniusTS\Preferences;


use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;
use GeniusTS\Preferences\Models\Setting;

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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'geniusts_preferences');

        $this->publishMigrations();
        $this->publishViews();
        $this->publishController();
        $this->registerSettings();

        $this->app->singleton('preferences', function ()
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
            return $view->with('preferences', $this->app['preferences'])
                ->with('version', $this->app->version());
        });
    }

    /**
     * Publish migration files
     */
    protected function publishMigrations()
    {
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
    }

    /**
     * Publish views
     */
    protected function publishViews()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/geniusts_preferences'),
        ], 'views');
    }

    /**
     * Publish the default controller
     */
    protected function publishController()
    {
        $this->publishes([
            __DIR__ . '/../resources/controllers' => base_path('app/Http/Controllers'),
        ], 'controller');
    }

    /**
     * Register Settings
     */
    protected function registerSettings()
    {
        try
        {
            $models = Setting::all();

            foreach ($models as $model)
            {
                Config::set("preferences.{$model->domain}.{$model->slug}", $model->value);
            }
        }
        catch (QueryException $e)
        {
        }
    }
}
