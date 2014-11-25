<?php

class DatabaseSeeder extends Seeder {
	private $adminUser = 'admin';
	private $adminPassword = '123456';
	
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		AdminUser::create( [
			'username' => $adminUser,
			'password' => Hash::make($adminPassword)
		] );
		
		AdminPermission::create( [
			'name' => 'Users',
			'description' => 'Manage users'
		] );
		
		// Grant all permissions to root user
		$root = AdminUser::where('username', '=', $adminUser)->first();
		$perms = AdminPermission::all();
		foreach($perms as $perm) {
			$root->permissions()->save($perm);
		}
		$root->save();
		
		// TODO: Remove seeded user neocarlitos for final release
		User::create( [ 
				'email' => 'neocarlitos@gmail.com',
				'name' => 'Neo',
				'password' => Hash::make('123456')
		] );

		// TODO: Remove seeded CellEx task for final release
		TaskType::create( [ 
				'name' => 'CellEx',
				'description' => 'Extracting cells from microscopic images'
		] );

		$cellExTask = TaskType::where('name', '=', 'CellEx')->first();
		Task::create( [ 
				'task_type' => $cellExTask->id,
				'data' => 'img/110803_a1_ch00.png'
		] );
	}
}
