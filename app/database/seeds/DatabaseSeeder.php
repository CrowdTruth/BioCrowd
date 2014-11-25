<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		User::create( [ 
				'email' => 'neocarlitos@gmail.com',
				'name' => 'Neo',
				'password' => Hash::make('123456')
		] );

		AdminUser::create( [ 
				'username' => 'admin',
				'password' => Hash::make('123456')
		] );

		AdminPermission::create( [ 
				'name' => 'Users',
				'description' => 'Manage users'
		] );
		
		TaskType::create( [ 
				'name' => 'CellEx',
				'description' => 'Extracting cells from microscopic images'
		] );

		$cellExTask = TaskType::where('name', '=', 'CellEx')->first();
		Task::create( [ 
				'task_type' => $cellExTask->id,
				'data' => 'img/110803_a1_ch00.png'
		] );

		// Grant all permissions to root user
		$root = AdminUser::where('username', '=', 'admin')->first();
		$perms = AdminPermission::all();
		foreach($perms as $perm) {
			$root->permissions()->save($perm);
		}
		$root->save();
	}
}
