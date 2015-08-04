<?php

/*
 * This file is part of Laravel CrowdAuth
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCrowdGroupsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crowd_groups', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('group_name');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('crowd_groups');
    }

}