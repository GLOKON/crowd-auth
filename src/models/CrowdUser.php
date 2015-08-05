<?php

/*
 * This file is part of CrowdAuth
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GLOKON\CrowdAuth\Models;

use \Illuminate\Auth\UserTrait;
use \Illuminate\Auth\UserInterface;
use \Illuminate\Auth\Reminders\RemindableTrait;
use \Illuminate\Auth\Reminders\RemindableInterface;

class CrowdUser extends \Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    protected $fillable = array('crowd_key', 'username', 'email', 'display_name', 'first_name', 'last_name');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'crowd_users';


    /**
    * Get all groups that belong to the user
    *
    * @return CrowdGroup
    */
    public function groups() {
        return $this->belongsToMany('GLOKON\CrowdAuth\Models\CrowdGroup', 'crowdgroup_crowduser', 'crowd_user_id', 'crowd_group_id');
    }


    /**
     * Determine if a Crowd User has a specific Group
     * @param $groupId
     * @return bool
     */
    public function userHasGroup($groupId) {
        return ! is_null(
            \DB::table('crowdgroup_crowduser')
              ->where('crowd_user_id', $this->id)
              ->where('crowd_group_id', $groupId)
              ->first()
        );
    }

}