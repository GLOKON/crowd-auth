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

class CreateCrowdgroupCrowduserTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crowdgroup_crowduser', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('crowd_group_id')->unsigned()->index();
            $table->integer('crowd_user_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('crowdgroup_crowduser', function(Blueprint $table)
        {
            $table->foreign('crowd_group_id')->references('id')->on('crowd_groups')->onDelete('cascade');
            $table->foreign('crowd_user_id')->references('id')->on('crowd_users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('crowdgroup_crowduser');
    }

}
