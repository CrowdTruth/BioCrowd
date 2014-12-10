<?php
/**
 * This Seeder populates the database with the basic information required by 
 * a new instance of the Dr. Detective game.
 */
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
		
		// TODO: Remove DevelopDBSeeder for final release
		$this->call('DevelopDBSeeder');
	}
}
