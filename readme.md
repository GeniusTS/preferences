# Preferences page for Laravel 5

This package to generate a preferences page to the application,
specially if you have many component that have a different settings 
and you want to store it in the database and use it by default config
functions of laravel.

- [Installation](#installation)
- [Usage](#usage)
- [License](#license)

## Installation

1. ***Download the package***
 
 * command line:

`composer require geniusts/preferences`

* or add it to composer file:

```json
{
    "require": {
        "geniusts/preferences": "^1.0"
    }
}
```
and run:
`composer update`

2. ***Service Provider***

Add the package to your application service providers in `config/app.php` file.

```php
    GeniusTS\Preferences\PreferencesServiceProvider::class,
```

3. ***Controller And Migrations***

Publish the package Controller file to your application. Run these commands:

    php artisan vendor:publish --provider="GeniusTS\Preferences\PreferencesServiceProvider" --tag=controller
   
You can also publish views and migrations by the following commands:

    php artisan vendor:publish --provider="GeniusTS\Preferences\PreferencesServiceProvider" --tag=views
    php artisan vendor:publish --provider="GeniusTS\Preferences\PreferencesServiceProvider" --tag=migrations

No need to publish the migrations files just run migrate command to execute the migrations.

    php artisan migrate

4. ***Routes and views***

Add two routes to you routes file:
    
    Route::get('/settings', 'SettingsController@edit')
        ->midllware(//Apply your middleware)
        
    Route::patch('/settings', 'SettingsController@update')
        ->midllware(//Apply your middleware)
        
Now you have to create a `preferences.settings` view with your app layout
and include the `geniusts_preferences::settings` view.

    @include('geniusts_preferences::settings');

## Usage

### Creating settings tab

1. ***create a view of the settings:***
Ex.: `settings/general.blade.php`

```html
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label> Test </label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="glyphicon glyphicon-alert"></i>
                    </div>
    
                    <input class="form-control" 
                           name="project_name"
                           value="{{ \GeniusTS\Preferences\Models\Setting::findBySlugOrNew('project_name')->value }}">
                </div>
            </div>
        </div>
    </div>
```

2. ***Register the tab to PreferencesManager*** 

```php
    use GeniusTS\Preferences\Models\Domain;
    use GeniusTS\Preferences\Models\Element;
    
    // Create a settings Domain
    // Domain(string $key, View $view, string $label)
    $domain = new Domain('general', view('settings.general'), 'General');
    
    // Add the inputs names and validation rules
    // Element(string $name, string $rules)
    $domain->addElement(new Element('project_name', 'required|max:255'));
    
    // register the Domain to the Preferences manager
    $manager = resolve('preferences'); // or app('preferences') for versions older than 5.3
    $manager->addDomain($domain);
```
 
> You can register the `domains` in the `boot` function of your
 package service provider.

### Access saved settings

Use the default `config` function or `Config` class to get the values of 
your settings: `config('preferences.{domain}.{element}')`

```php
config('preferences.general.project_name');
```

## License

This package is free software distributed under the terms of the MIT license.
