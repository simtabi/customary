## Customary Laravel Settings

Use `simtabi/customary` to store key value pair settings in the database.

> All the settings saved in db is cached to improve performance by reducing sql query to zero.

### Installation

**1** - You can install the package via composer:

```bash
$ composer require simtabi/customary
```

**2** - If you are installing on Laravel 5.4 or lower you will be needed to manually register Service Provider by adding it in `config/app.php` providers array and Facade in aliases arrays.

```php
'providers' => [
    //...
    Simtabi\Customary\CustomaryServiceProvider::class
]

'aliases' => [
    //...
    "Settings" => Simtabi\Customary\Facade::class
]
```

In Laravel 5.5 or above the service provider automatically get registered and a facade `Setting::get('app_name')` will be available.

**3** - Now run the migration by `php artisan migrate` to create the settings table.

Optionally you can publish migration by running

```
php artisan vendor:publish --provider="Simtabi\Settings\SettingsServiceProvider" --tag="migrations"
```

### Getting Started

You can use helper function `customary('app_name')` or `Customary::get('app_name')` to use laravel settings.

### Available methods

```php
// Pass `true` to ignore cached settings
customary()->all($fresh = false);

// Get a single setting
customary()->get($key, $defautl = null);

// Set a single setting
customary()->set($key, $value);

// Set a multiple settings
customary()->set([
   'app_name' => 'simtabi',
   'app_email' => 'info@simtabi.com',
   'app_type' => 'SaaS'
]);

// check for setting key
customary()->has($key);

// remove a setting
customary()->remove($key);
```

### Groups

You have all above methods available just set you working group by calling `->group('group_name')` method and chain on:

```php
customary()->group('team.1')->set('app_name', 'My Team App');
customary()->group('team.1')->get('app_name');
> My Team App

customary()->group('team.2')->set('app_name', 'My Team 2 App');
customary()->group('team.2')->get('app_name');
> My Team 2 App

// You can use facade
\Customary::group('team.1')->get('app_name')
> My Team App
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

```bash
$ composer test
```

### Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please use the issue tracker.

### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


## Credits
- https://github.com/qcod/laravel-settings
- https://github.com/laraeast/laravel-settings
