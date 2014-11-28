<?php

class DevelopDBSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->command->info('Create test users: neocarlitos@... loeloe87@...');
		User::create( [ 
				'email' => 'neocarlitos@gmail.com',
				'name' => 'Neo',
				'password' => Hash::make('123456')
		] );
		
		User::create( [
			'email' => 'loeloe87@hotmail.com',
			'name' => 'Merel',
			'password' => Hash::make('Merel')
		] );

		$this->command->info('Create test CellExGameType');
		$gameType = new GameType(new CellExGameType());
		$gameType->save();
		
		$game = new Game($gameType);
		$game->level = 1;
		$game->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a1_ch00.png');
		$data = 'img/110803_a1_ch00.png';
		$task = new Task($game, $data);
		$task->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a1_ch01.png');
		$data = 'img/110803_a1_ch01.png';
		$task = new Task($game, $data);
		$task->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a1_ch02.png');
		$data = 'img/110803_a1_ch02.png';
		$task = new Task($game, $data);
		$task->save();
		
		$game = new Game($gameType);
		$game->level = 2;
		$game->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a1_ch00.png');
		$data = 'img/110803_a1_ch00.png';
		$task = new Task($game, $data);
		$task->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a1_ch01.png');
		$data = 'img/110803_a1_ch01.png';
		$task = new Task($game, $data);
		$task->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a1_ch02.png');
		$data = 'img/110803_a1_ch02.png';
		$task = new Task($game, $data);
		$task->save();
	}
}
