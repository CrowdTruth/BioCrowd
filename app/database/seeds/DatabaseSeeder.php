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

		// $this->call('UserTableSeeder');
		User::create(array(
				'email' => 'neocarlitos@gmail.com',
				'name' => 'Neo',
				'password' => Hash::make('123456')
		));

		AdminUser::create(array(
				'username' => 'admin',
				'password' => Hash::make('123456'),
				'permissions' => 1
		));
	}
}
