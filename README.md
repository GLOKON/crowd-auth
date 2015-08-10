CrowdAuth
========

[![Build Status](https://travis-ci.org/GLOKON/crowd-auth.svg)](https://travis-ci.org/GLOKON/crowd-auth)
[![Latest Stable Version](https://poser.pugx.org/glokon/crowd-auth/v/stable)](https://packagist.org/packages/glokon/crowd-auth)
[![Total Downloads](https://poser.pugx.org/glokon/crowd-auth/downloads)](https://packagist.org/packages/glokon/crowd-auth)
[![Latest Unstable Version](https://poser.pugx.org/glokon/crowd-auth/v/unstable)](https://packagist.org/packages/glokon/crowd-auth)
[![License](https://poser.pugx.org/glokon/crowd-auth/license)](https://packagist.org/packages/glokon/crowd-auth)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GLOKON/crowd-auth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GLOKON/crowd-auth/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/GLOKON/crowd-auth/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GLOKON/crowd-auth/?branch=master)

A simple way to implement Atlassian Crowd Authentication into your application.

**SUPPORTED VERSIONS:** Atlassian Crowd 2.1 and later versions only.

## Quick start

### Laravel 4.2.x

In the `require` key of `composer.json` file add the following

    "glokon/crowd-auth": "*"

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

Now generate the Crowd Auth migrations (make sure you have your database configuration set up):

    $ php artisan migrate --package="glokon/crowd-auth"

This will setup three tables - `crowd_users`, `crowd_groups` and `crowdgroup_crowduser`.

Now publish the config files for this package:

    $ php artisan config:publish "glokon/crowd-auth"

Once the configuration is published go to your `config/packages/glokon/crowd-auth/crowdauth.php` and configure your Atlassian Crowd settings.

After you have configured your Atlassian Crowd settings you need to change the `driver` setting in `config/auth.php` to:

```php
'driver' => 'crowd-auth',
```

Once all this is completed you can simply use `Auth::Attempt()` and it will attempt to login using your Atlassian Crowd server.