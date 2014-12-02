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
			$table->string('password');
			$table->string('remember_token');
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
			$table->integer('game_type')->unsigned();
			$table->foreign('game_type')->references('id')->on('game_types');
			$table->integer('level')->default(1);
			$table->string('name');
			$table->string('instructions');
		});
		
		Schema::create('tasks', function($table)
		{
			$table->increments('id');
			$table->integer('game_id')->unsigned();
			$table->foreign('game_id')->references('id')->on('games');
			$table->string('data');
			$table->timestamps();
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
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('judgements');
		Schema::drop('tasks');
		Schema::drop('games');
		Schema::drop('game_types');
		Schema::drop('admin_permission_admin_user');
		Schema::drop('admin_users');
		Schema::drop('admin_permissions');
		Schema::drop('users');
	}
}
