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

		User::create(array(
				'email' => 'neocarlitos@gmail.com',
				'name' => 'Neo',
				'password' => Hash::make('123456')
		));

		AdminUser::create(array(
				'username' => 'admin',
				'password' => Hash::make('123456')
		));

		AdminPermission::create(array(
				'name' => 'Users',
				'description' => 'Manage users'
		));

		AdminPermission::create(array(
				'name' => 'Games',
				'description' => 'Manage games'
		));

		// Grant all permissions to root user
		$root = AdminUser::where('username', '=', 'admin')->first();
		$perms = AdminPermission::all();
		foreach($perms as $perm) {
			$root->permissions()->save($perm);
		}
		$root->save();
	}
}
