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
		$game->name = 'Cell tagging';
		$game->instructions = ''
				.'<p>In the image below one or more cells are displayed. </p>'
				.'<p>Draw a square around each cell, even if they are lying partly behind other cells/debris. When two cells are overlapping, you may draw overlapping squares.</p>'
				.'<p>Do not count cells that are less then 50% visible</p>'
				.'<p>'
				.'Examples:'
				.'</p>'
				.'<img src="img/CellEx_instructions.png">';
		$game->save();
		
		$images = glob('public/img/11*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: CellEx with image: '.$image);
			$data = $image;
			$task = new Task($game, $data);
			$task->save();
		}
		
		$game = new Game($gameType);
		$game->level = 2;
		$game->name = 'Nucleus tagging';
		$game->instructions = ''
				.'<p>In the image below one or more cells are displayed. </p>'
				.'<p>Draw a square around each nucleus, even if they are lying partly behind other cells/debris. When the nucleus does lie partly behind other cells and this is making two or more nuclei overlap, you may draw overlapping squares.</p>'
				.'<p>Do not count nuclei that are less then 50% visible</p>'
				.'<p>'
				.'Examples:'
				.'</p>'
				.'<img src="img/NuclEx_instructions.png">';
		$game->save();
		
		$images = glob('public/img/11*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: CellEx with image: '.$image);
			$data = $image;
			$task = new Task($game, $data);
			$task->save();
		}
		
		$game = new Game($gameType);
		$game->level = 3;
		$game->name = 'Colony tagging';
		$game->instructions = ''
				.'<p>In the image below one or more agar colonies are displayed. </p>'
				.'<p>Draw a square around each colony, even if they are touching other colonies. </p>'
				.'<p>When the colony touches other colonies, you may draw overlapping squares, as long as you know for sure that the colony you tagged is in fact ONE colony.</p>'
				.'<p>Do not count colonies that are less then 50% visible</p>'
				.'<p>'
				.'Examples:'
				.'</p>'
				.'<img src="img/ColEx_instructions.png">';
		$game->save();
		
		$images = glob('public/img/agar/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: ColEx with image: '.$image);
			$data = $image;
			$task = new Task($game, $data);
			$task->save();
		}
		
		
		
		$this->command->info('Create test VesExGameType');
		$gameType = new GameType(new VesExGameType());
		$gameType->save();
		
		$game = new Game($gameType);
		$game->level = 1;
		$game->name = 'Vesicle locating';
		$game->instructions = ''
				.'<p>In the image below, one cell which contains vesicles is displayed. </p>'
				.'<p>In this image, the vesicles appear brighter then the rest. </p>'
				.'<p>Tick the boxes below the image if the statement is true. </p>'
				.'<p>Pay attention! The second and third statements are uninteresting when the first statement is true, so they will disappear when the first staement is ticked. </p>'
				.'<p>'
				.'Example:'
				.'</p>'
				.'<img src="img/VesEx_instructions.png">';
		$game->save();
		
		$images = glob('public/img/vesEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: VesEx with image: '.$image);
			$data = $image;
			$task = new Task($game, $data);
			$task->save();
		}
		
		/*
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
		*/
	}
}
