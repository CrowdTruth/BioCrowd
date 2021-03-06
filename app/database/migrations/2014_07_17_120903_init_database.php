<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitDatabase extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('name');
			$table->integer('level')->default(1);
			$table->string('title')->default('Novice');
			$table->integer('score')->default(0);
			$table->string('password');
			$table->string('cellBioExpertise')->nullable();
			$table->string('expertise')->nullable();
			$table->boolean('guest_user');
			$table->rememberToken();
			$table->timestamps();
		});*/

		Schema::create('admin_users', function($table)
		{
			$table->increments('id');
			$table->string('username')->unique();
			$table->string('password');
			$table->timestamps();
		});

		Schema::create('admin_permissions', function($table)
		{
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('description');
		});

		Schema::create('admin_permission_admin_user', function($table)
		{
			$table->integer('admin_user_id')->unsigned();
			$table->foreign('admin_user_id')->references('id')->on('admin_users');
			$table->integer('admin_permission_id')->unsigned();
			$table->foreign('admin_permission_id')->references('id')->on('admin_permissions');
		});
		
		/*Schema::create('user_preferences', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('campaignsNotification');
			$table->string('newsNotification');
			$table->string('playReminder');
			$table->string('badgesSection');
			$table->string('scoresSection');
			$table->string('accountSection');
			$table->string('passwordSection');
			$table->string('notificationsSection');
			$table->timestamps();
		});*/
		
		Schema::create('levels', function($table)
		{
			$table->increments('id');
			$table->integer('level');
			$table->integer('max_score');
			$table->string('title');
			$table->timestamps();
		});
		
		Schema::create('game_types', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('description');
			$table->string('handler_class');
			$table->string('thumbnail');
		});
		
		Schema::create('games', function($table)
		{
			$table->increments('id');
			$table->integer('game_type_id')->unsigned();
			$table->foreign('game_type_id')->references('id')->on('game_types');
			$table->integer('level')->default(1);
			$table->string('name');
			$table->string('tag');
			$table->longText('instructions');
			$table->longText('examples');
			$table->longText('steps');
			$table->text('extraInfo');
			$table->integer('score');
			$table->string('description');
		});
		
		Schema::create('task_types', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('description');
			$table->string('handler_class');
			$table->string('thumbnail');
		});
		
		Schema::create('tasks', function($table)
		{
			$table->increments('id');
			$table->integer('task_type_id')->unsigned();
			$table->foreign('task_type_id')->references('id')->on('task_types');
			$table->string('data');
			$table->string('unit_id')->nullable();
			$table->timestamps();
		});
		
		Schema::create('workflows', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('tasks');
			$table->integer('generate_task_type')->unsigned();
			$table->foreign('generate_task_type')->references('id')->on('task_types');
		});
		
		Schema::create('game_has_task', function($table)
		{
			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('tasks');
			$table->integer('game_id')->unsigned();
			$table->foreign('game_id')->references('id')->on('games');
		});
		
		Schema::create('campaign_types', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('description');
			$table->string('handler_class');
			$table->string('thumbnail');
		});
		
		Schema::create('campaigns', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('tag');
			$table->string('badgeName');
			$table->longText('description');
			$table->string('image');
			$table->timestamp('startDate');
			$table->timestamp('endDate');
			$table->integer('score')->default(0);
			$table->integer('targetNumberAnnotations');
			$table->integer('campaign_type_id')->unsigned();
			$table->foreign('campaign_type_id')->references('id')->on('campaign_types');
			$table->boolean('sendsEmail');
			$table->string('emailCondition');
			$table->timestamps();
		});
		
		Schema::create('judgements', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('tasks');
			$table->integer('game_id')->unsigned();
			$table->foreign('game_id')->references('id')->on('games');
			$table->integer('campaign_id')->unsigned()->nullable();
			$table->foreign('campaign_id')->references('id')->on('campaigns')->nullable();
			$table->longText('response');
			$table->string('flag')->nullable();
			$table->timestamps();
		});
		
		Schema::create('scores', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('game_id')->nullable()->default(null)->unsigned();
			$table->foreign('game_id')->references('id')->on('games');
			$table->integer('campaign_id')->nullable()->default(null)->unsigned();
			$table->foreign('campaign_id')->references('id')->on('campaigns');
			$table->integer('score_gained');
			$table->string('description');
			$table->timestamps();
		});
		
		Schema::create('campaign_progress', function($table)
		{
			$table->increments('id');
			$table->integer('campaign_id')->unsigned();
			$table->foreign('campaign_id')->references('id')->on('campaigns');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('number_performed')->default(0);
			$table->integer('times_finished')->default(0);
			$table->timestamps();
		});
		
		Schema::create('campaign_has_game', function($table)
		{
			$table->increments('id');
			$table->integer('campaign_id')->unsigned();
			$table->foreign('campaign_id')->references('id')->on('campaigns');
			$table->integer('game_id')->unsigned();
			$table->foreign('game_id')->references('id')->on('games');
			$table->timestamps();
		});
		
		Schema::create('stories', function($table)
		{
			$table->increments('id');
			$table->integer('campaign_id')->unsigned();
			$table->foreign('campaign_id')->references('id')->on('campaigns');
			$table->text('extraInfo');
			$table->longtext('story_string');
			$table->timestamps();
		});
		
		Schema::create('campaign_has_story', function($table)
		{
			$table->increments('id');
			$table->integer('campaign_id')->unsigned();
			$table->foreign('campaign_id')->references('id')->on('campaigns');
			$table->integer('story_id')->unsigned();
			$table->foreign('story_id')->references('id')->on('stories');
			$table->timestamps();
		});
		
		/*Schema::create('ranks', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('currentRank');
			$table->integer('previousRank');
			$table->timestamps();
		});*/
		
		Schema::create('badges', function($table)
		{
			$table->increments('id');
			$table->integer('campaign_id')->nullable()->default(null)->unsigned();
			$table->foreign('campaign_id')->references('id')->on('campaigns');
			$table->string('name');
			$table->string('image');
			$table->string('text');
			$table->timestamps();
		 });
		
		Schema::create('user_has_badge', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('badge_id')->unsigned();
			$table->foreign('badge_id')->references('id')->on('badges');
			$table->timestamps();
		});
		
		/*Schema::create('user_actions', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('page');
			$table->string('action');
			$table->timestamps();
		});*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//Schema::drop('user_actions');
		Schema::drop('user_has_badge');
		Schema::drop('badges');
		//Schema::drop('ranks');
		Schema::drop('campaign_has_story');
		Schema::drop('stories');
		Schema::drop('campaign_has_game');
		Schema::drop('campaign_progress');
		Schema::drop('scores');
		Schema::drop('judgements');
		Schema::drop('campaigns');
		Schema::drop('campaign_types');
		Schema::drop('game_has_task');
		Schema::drop('workflows');
		Schema::drop('tasks');
		Schema::drop('task_types');
		Schema::drop('games');
		Schema::drop('game_types');
		Schema::drop('levels');
		//Schema::drop('user_preferences');
		Schema::drop('admin_permission_admin_user');
		Schema::drop('admin_users');
		Schema::drop('admin_permissions');
		//Schema::drop('users');
	}
}
