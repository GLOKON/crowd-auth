<?php

/*
 * This file is part of Laravel CrowdAuth
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return array(

	/*
	|--------------------------------------------------------------------------
	| Crowd Auth: Crowd URL
	|--------------------------------------------------------------------------
	| Please specify the URL to your crowd service for authentication, it must
	| end in a forward slash and be a publicly accesible URL.
	*/

	'url' => 'http://crowd.example.com:8080/crowd/',

	/*
	|--------------------------------------------------------------------------
	| Crowd Auth: Application Name
	|--------------------------------------------------------------------------
	| Here is where you specify your application name that you use for your
	| crowd application.
	*/

	'app_name' => 'crowd-app-name',

	/*
	|--------------------------------------------------------------------------
	| Crowd Auth: Application Password
	|--------------------------------------------------------------------------
	| Here is where you specify your password that you use for your crowd
	| application.
	*/

	'app_password' => 'crowd-app-password',

	/*
	|--------------------------------------------------------------------------
	| Crowd Auth: Usable User Groups
	|--------------------------------------------------------------------------
    |
    | Here is where you define each of the groups that have access to your 
    | application.
	*/

	'app_groups' => array(

		'crowd-administrators',

		'crowd-users',

	),

);
