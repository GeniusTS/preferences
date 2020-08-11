# Preferences page for Laravel

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
        "geniusts/preferences": "^2.0"
    }
}
```
and run:
`composer update`

2. ***Service Provider***

- If you are using Laravel >=5.5 and the `auto-discover` is enabled, no need to do anything.
- If your Laravel <5.5 or you disabled `auto-discover` add the ServiceProvider to the providers array in `config/app.php`

```php
    GeniusTS\Preferences\PreferencesServiceProvider::class,
```

**Note:** If you are using Laravel 5.5 or greater no need to add it, It will auto discove

3. ***Controller And Migrations***

Publish the package Controller file to your application. Run these commands:

    php artisan vendor:publish --provider="GeniusTS\Preferences\PreferencesServiceProvider" --tag=controller
   
You can also publish views and migrations by the following commands:

    php artisan vendor:publish --provider="GeniusTS\Preferences\PreferencesServiceProvider" --tag=views
    php artisan vendor:publish --provider="GeniusTS\Preferences\PreferencesServiceProvider" --tag=migrations

No need to publish the migrations files just run migrate command to execute the migrations.

    php artisan migrate

> If you want to use `DB` transaction while saving the data, add `protected $transactions = true;` to `SettingsController`.


4. ***Routes and views***

Add two routes to you routes file:
    
    Route::get('/settings/{preferences_domain?}', 'SettingsController@edit')
        ->midllware(//Apply your middleware)
        
    Route::patch('/settings/{preferences_domain?}/{preferences_element?}', 'SettingsController@update')
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
                           name="general[project_name]"
                           value="{{ old('general.project_name', config('preferences.general.project_name')) }}">
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
    // you can use label like 'labels.general', because the view execute "trans" function
    $domain = new Domain('general', view('settings.general'), 'General');
    
    // Add the inputs names and validation rules
    // Element(string $name, mixed $rules)
    $domain->addElement(new Element('project_name', 'required|max:255'));
    
    // OR for array values
    $domain->addElement(new Element('options', ['options' => 'array', 'options.*' => 'required|integer']));
    
    
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
## Migration from version 1

1. update inputs name inside preferences views. 
Ex: You have a `general` domain that have `project_name` element, then the input name should be `general[project_name]`.

2. Update declaration of `edit` and `handleSuccessResponse` methods of `SettingsController` inside your project.
```php
   public function edit($domain = null) {
        // Logic here
   }
       
    protected function handleSuccessResponse($domain, $element) {
        // Logic here    
    }
```

## License

This package is free software distributed under the terms of the MIT license.
