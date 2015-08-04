<?php

/*
 * This file is part of Laravel CrowdAuth
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GLOKON\CrowdAuth;

use Illuminate\Auth\UserProviderInterface;
use Illuminate\Auth\GenericUser;

use GLOKON\CrowdAuth\Models\CrowdUser;
use GLOKON\CrowdAuth\Models\CrowdGroup;

class CrowdAuthUserProvider implements UserProviderInterface {

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveById($identifier) {
        if($identifier != null) {
            if(\App::make('crowd-auth')->doesUserExist($identifier)) {
                $userData = \App::make('crowd-auth')->getUser($identifier);
                if($userData != null) {
                    return new GenericUser([
                            'id' => $userData['user-name'],
                            'username' => $userData['user-name'],
                            'key' => $userData['key'],
                            'displayName' => $userData['display-name'],
                            'firstName' => $userData['first-name'],
                            'lastName' => $userData['last-name'],
                            'email' => $userData['email'],
                            'usergroups' => $userData['groups'],
                        ]);
                }
            }
        }
        return null;
    }


    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByCredentials(array $credentials) {
        if (isset($credentials['username'])) {
            return $this->retrieveById($credentials['username']);
        }
        return null;
    }


    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Auth\UserInterface  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(\Illuminate\Auth\UserInterface $user, array $credentials) {
        if(\App::make('crowd-auth')->canUserLogin($credentials['username'])) {
            $token = \App::make('crowd-auth')->ssoAuthUser($credentials);
            if($token != null && \App::make('crowd-auth')->ssoGetUser($credentials['username'], $token) != null) {
                // Check if user exists in DB, if not add it.
                $stored_crowd_user = CrowdUser::where('crowd_key', '=', $user->key)->first();
                if($stored_crowd_user == null) {
                    $stored_crowd_user = CrowdUser::create(array(
                        'crowd_key' => $user->key,
                        'username' => $user->username,
                        'email' => $user->email,
                        'display_name' => $user->displayName,
                        'first_name' => $user->firstName,
                        'last_name' => $user->lastName,
                    ));
                }
                // Detach all old groups from user and re-attach current ones.
                $stored_crowd_user->groups()->detach();
                foreach($user->usergroups as $usergroup) {
                    // Check if usergroup already exists in the DB, if not add it.
                    $crowdUserGroup = CrowdGroup::where('group_name', '=', $usergroup)->first();
                    if($crowdUserGroup == null) {
                        $crowdUserGroup = CrowdGroup::create(array(
                            'group_name' => $usergroup,
                        ));
                    }

                    // Check if user has a group retrieved from Crowd
                    if($stored_crowd_user->userHasGroup($crowdUserGroup->id) == false) {
                        $stored_crowd_user->groups()->attach($crowdUserGroup);
                    }

                }
                $stored_crowd_user->save();
                $user->setRememberToken($token);
                return true;
            }
        }
        return false;
    }


    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByToken($identifier, $token) {
        $userData = \App::make('crowd-auth')->ssoGetUser($identifier, $token);
        if($userData != null) {
            return $this->retrieveById($userData['user-name']);
        }
        return null;
    }


    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Auth\UserInterface  $user
     * @param  string  $token
     * @return null
     */
    public function updateRememberToken(\Illuminate\Auth\UserInterface $user, $token) {
        if($user != null) {
            $user->setRememberToken(\App::make('crowd-auth')->ssoUpdateToken($token));
        }
        return null;
    }
}