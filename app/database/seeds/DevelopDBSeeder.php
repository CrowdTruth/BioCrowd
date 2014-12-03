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
		
		for($i=1;$i<=60;$i++){
			User::create( [
			'email' => 'user'.$i.'@test.com',
			'name' => 'User'.$i,
			'password' => Hash::make('User'.$i)
			] );
		}

		$this->command->info('Create test CellExGameType');
		$gameType = new GameType(new CellExGameType());
		$gameType->save();
		
		$game = new Game($gameType);
		$game->level = 1;
		$game->name = 'Basic cell tagging';
		$game->instructions = ''
				.'<p>In the image below one or more cells are displayed. </p>'
				.'<p>Draw a square around each cell, even if they are lying partly behind other cells/debris. When two cells are overlapping, you may draw overlapping squares.</p>'
				.'<p>Do not count cells that are less then 50% visible</p>';
		$game->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a1_ch00.png');
		$data = 'img/110803_a1_ch00.png';
		$task = new Task($game, $data);
		$task->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a1b_ch00.png');
		$data = 'img/110803_a1b_ch00.png';
		$task = new Task($game, $data);
		$task->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a2a_ch00.png');
		$data = 'img/110803_a2a_ch00.png';
		$task = new Task($game, $data);
		$task->save();
		
		$game = new Game($gameType);
		$game->level = 2;
		$game->name = 'Advanced cell tagging';
		$game->instructions = ''
				.'<p>In the image below one or more cells are displayed. </p>'
				.'<p>CLICK on all the cells to DRAW A SQUARE AROUND THE CELL NUCLEUS, even if they are lying partly behind other cells/debris. When two cells are overlapping, you may draw overlapping squares.</p>';
		$game->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a1_ch00.png');
		$data = 'img/110803_a1_ch00.png';
		$task = new Task($game, $data);
		$task->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a1b_ch00.png');
		$data = 'img/110803_a1b_ch00.png';
		$task = new Task($game, $data);
		$task->save();
		
		$this->command->info('Create test Game: CellEx with image: 110803_a2a_ch00.png');
		$data = 'img/110803_a2a_ch00.png';
		$task = new Task($game, $data);
		$task->save();
		
		$this->command->info('Create test DummyGameType');
		$gameType = new GameType(new DummyGameType());
		$gameType->save();
		
		$game = new Game($gameType);
		$game->level = 1;
		$game->name = 'Dummy task';
		$game->instructions = 'Some instructions.';
		$game->save();
		
		$this->command->info('Create test Game: Dummy');
		$task = new Task($game, 'Dummy task 1');
		$task->save();
		$task = new Task($game, 'Dummy task 2');
		$task->save();
	}
}
