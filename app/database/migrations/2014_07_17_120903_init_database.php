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
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('email')->unique();
			$table->string('name');
			$table->integer('level')->default(1);
			$table->string('title')->default('Novice');
			$table->integer('score')->default(0);
			$table->string('password');
			$table->rememberToken();
			$table->timestamps();
		});

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
		
		Schema::create('games', function($table)
		{
			$table->increments('id');
			$table->integer('level')->default(1);
			$table->string('name');
			$table->longText('instructions');
			$table->text('extraInfo');
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
		
		Schema::create('judgements', function($table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('tasks');
			$table->longText('response');
			$table->timestamps();
		});
		
		Schema::create('campaign_types', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('description');
		});
		
		Schema::create('campaigns', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->longText('description');
			$table->string('image');
			$table->timestamp('startDate');
			$table->timestamp('endDate');
			$table->integer('targetNumberAnnotations');
			$table->integer('campaign_type_id')->unsigned();
			$table->foreign('campaign_type_id')->references('id')->on('campaign_types');
			$table->boolean('sendsEmail');
			$table->string('emailCondition');
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
			$table->timestamps();
		});
		
		Schema::create('campaign_games', function($table)
		{
			$table->increments('id');
			$table->integer('campaign_id')->unsigned();
			$table->foreign('campaign_id')->references('id')->on('campaigns');
			$table->integer('game_id')->unsigned();
			$table->foreign('game_id')->references('id')->on('games');
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
		Schema::drop('campaign_games');
		Schema::drop('campaign_progress');
		Schema::drop('campaigns');
		Schema::drop('campaign_types');
		Schema::drop('judgements');
		Schema::drop('game_has_task');
		Schema::drop('workflows');
		Schema::drop('tasks');
		Schema::drop('task_types');
		Schema::drop('games');;
		Schema::drop('admin_permission_admin_user');
		Schema::drop('admin_users');
		Schema::drop('admin_permissions');
		Schema::drop('users');
	}
}
