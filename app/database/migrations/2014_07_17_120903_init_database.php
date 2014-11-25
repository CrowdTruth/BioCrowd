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
		
		Schema::create('task_types', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('description');
		});
		
		Schema::create('tasks', function($table)
		{
			$table->increments('id');
			$table->integer('task_type')->unsigned();
			$table->foreign('task_type')->references('id')->on('task_types');
			$table->string('data');
			$table->longText('response');
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
		Schema::drop('users');
		Schema::drop('admin_permission_admin_user');
		Schema::drop('admin_users');
		Schema::drop('admin_permissions');
		Schema::drop('tasks');
		Schema::drop('task_types');
	}
}
