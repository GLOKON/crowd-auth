Laravel CrowdAuth
========

[![Build Status](https://travis-ci.org/GLOKON/laravel-crowd-auth.svg)](https://travis-ci.org/GLOKON/laravel-crowd-auth)
[![Latest Stable Version](https://poser.pugx.org/glokon/laravel-crowd-auth/v/stable)](https://packagist.org/packages/glokon/laravel-crowd-auth)
[![Total Downloads](https://poser.pugx.org/glokon/laravel-crowd-auth/downloads)](https://packagist.org/packages/glokon/laravel-crowd-auth)
[![Latest Unstable Version](https://poser.pugx.org/glokon/laravel-crowd-auth/v/unstable)](https://packagist.org/packages/glokon/laravel-crowd-auth)
[![License](https://poser.pugx.org/glokon/laravel-crowd-auth/license)](https://packagist.org/packages/glokon/laravel-crowd-auth)

A simple way to implement Atlassian Crowd Authentication into your application.

## Quick start

### Laravel 4.2.x

In the `require` key of `composer.json` file add the following

    "glokon/laravel-crowd-auth": "*"

Run the Composer update comand

    $ composer update

In your `config/app.php` add `'GLOKON\CrowdAuth\CrowdAuthServiceProvider'` to the end of the `providers` array

```php
'providers' => array(

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...
    'GLOKON\CrowdAuth\CrowdAuthServiceProvider',
),
```

Now generate the crowd auth migration (make sure you have your database configuration set up):

    $ php artisan migrate --package="glokon/laravel-crowd-auth"

It will setup two tables - crowd_users and crowd_groups.

Now publish the config files for this package:

    $ php artisan config:publish "glokon/laravel-crowd-auth"

Once the configuration is published go to your `config/packages/glokon/laravel-crowd-auth/crowdauth.php` and configure the crowd settings.

After you have configured the crowd settings you need to change the `driver`, `model` and `table` in `config/auth.php` to:

```php
'driver' => 'crowdauth',
...
'model' => 'CrowdUser',
...
'table' => 'crowd_users',
```

Once all this is completed you can simply use `Auth::Attempt()` and it will attempt to login using the crowd server.