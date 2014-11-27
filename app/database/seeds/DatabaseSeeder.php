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

		$this->command->info('Create admin user: '.($this->adminUser).'...');
		AdminUser::create( [
			'username' => $this->adminUser,
			'password' => Hash::make($this->adminPassword)
		] );
		
		AdminPermission::create( [
			'name' => 'Users',
			'description' => 'Manage users'
		] );
		
		// Grant all permissions to root user
		$this->command->info('Grant ALL permissions to '.$this->adminUser.'...');
		$root = AdminUser::where('username', '=', $this->adminUser)->first();
		$perms = AdminPermission::all();
		foreach($perms as $perm) {
			$root->permissions()->save($perm);
		}
		$root->save();
		
		// TODO: Remove seeded user neocarlitos for final release
		$this->command->info('Create test user neocarlitos@gmail.com');
		User::create( [ 
				'email' => 'neocarlitos@gmail.com',
				'name' => 'Neo',
				'password' => Hash::make('123456')
		] );

		// TODO: Remove seeded CellEx task for final release
		$this->command->info('Create test CellExTaskType');
		$taskType = new TaskType(new CellExTaskType());
		$taskType->save();

		$this->command->info('Create test Task: CellEx with image: 110803_a1_ch00.png');
		$cellExTask = TaskType::where('name', '=', 'CellEx')->first();
		Task::create( [ 
				'task_type' => $cellExTask->id,
				'data' => 'img/110803_a1_ch00.png'
		] );
	}
}
