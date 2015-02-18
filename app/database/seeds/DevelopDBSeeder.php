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
				'password' => Hash::make('123456'),
				'level' => '8',
				'title' => 'Black belt',
				'score' => '999'
		] );
		
		User::create( [
			'email' => 'loeloe87@hotmail.com',
			'name' => 'Merel',
			'level' => '2',
			'title' => 'Padawan',
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
		$game->extraInfo = serialize([ 'label' => 'There are no cells in this image' ]);
		$game->save();
		
		$images = glob('public/img/cellExAndNuclEx/*');
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
		$game->extraInfo = serialize([ 'label' => 'There are no nuclei in this image' ]);
		$game->save();
		
		$images = glob('public/img/cellExAndNuclEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: NuclEx with image: '.$image);
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
		$game->extraInfo = serialize([ 'label' => 'There are no colonies in this image' ]);
		$game->save();
		
		$images = glob('public/img/colEx/*');
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
				.'<p>In the image below, one or more cells which contain vesicles are displayed. </p>'
				.'<p>If there is more then one cell completely visible, we want you to annotate the cell with the red border around it. </p>'
				.'<p>In this image, the vesicles appear brighter then the rest. </p>'
				.'<p>Tick the boxes below the image if the statement is true. </p>'
				.'<p>Pay attention! The second and third statements are uninteresting when the first statement is true, so they will disappear when the first staement is ticked. </p>'
				.'<p>'
				.'Example:'
				.'</p>'
				.'<img src="img/VesEx_instructions.png">';
		$game->extraInfo = '';
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
		
		$this->command->info('Create test Campaign');
		$campaign = new Campaign();
		$campaign->save();
		
		$campaign->level = 1;
		$campaign->name = 'Army Mission';
		$temp_game_array = [1,1,2,3,4];
		$campaign->game_array = serialize($temp_game_array);
		$story_array[0] = ''
				.'<p>In this campaign you will be working for the army. </p>';
		$story_array[1] = ''
				.'<p>The enemy has a small base in the desert. It is very important to estimate the amount of troops the enemy has in that base. 
						In order to do this, you must count the amount of tents/buildings the enemy has built there. Be careful! The enemy has 
						tried to hide their buildings by putting them in the awning of the walls. Good luck!</p>';
		$story_array[2] = ''
				.'<p>The building count has given us a good sense of their numbers, well done. Now we need to know the blueprint of 
						their main building, where we will begin our assault. We have used a satellite with X-ray vision to see the 
						walls of the building. <Example of a room> Put a marking on each room you see. Be careful! Some rooms might lie 
						on top of other rooms and seem to overlap in the picture. Count these as two separate rooms. </p>';
		$story_array[3] = ''
				.'<p>The blueprint you constructed in last assignment has given us a new insight: the people that are standing in the rooms can 
						be seen in the x-ray photos as well! This would give us an even more accurate count of the amount of enemies in the base. 
						<Example of an enemy head> Put a marking on each enemy you see. Be careful! Some enemies might seem to overlap since 
						they are on different stories of the building. Count these as two separate enemies. </p>';
		$story_array[4] = ''
				.'<p>Oh dear. Some people have made mistakes in marking the enemies. However, we don’t know who made the mistakes. We 
						would like you to go over this marking document and add any enemies that haven’t been marked yet and delete any 
						false markings. Keep in mind that we don’t know who made the mistakes, so you might not find any mistakes! </p>';
		$campaign->story = serialize($story_array);
		$campaign->image = 'img/army_mission.png';
		$campaign->save();
		
	}
}
