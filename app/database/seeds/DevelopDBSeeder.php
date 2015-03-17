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
		
		$game1 = new Game($gameType);
		$game1->level = 1;
		$game1->name = 'Cell tagging';
		$game1->instructions = ''
				.'<p>In the image below one or more cells are displayed. </p>'
				.'<p>Draw a square around each cell, even if they are lying partly behind other cells/debris. When two cells are overlapping, you may draw overlapping squares.</p>'
				.'<p>Do not count cells that are less then 50% visible</p>'
				.'<p>'
				.'Examples:'
				.'</p>'
				.'<img src="img/CellEx_instructions.png">';
		$game1->extraInfo = serialize([ 'label' => 'There are no cells in this image' ]);
		$game1->save();
		
		$this->command->info('Create test TaskType');
		$taskType = new TaskType(new CellExTaskType());
		$taskType->save();
		
		$images = glob('public/img/cellExAndNuclEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test CellExTask with image: '.$image);
			$data = $image;
			$cellExTask = new Task($taskType, $data);
			$cellExTask->save();
		}
		
		/*$game2 = new Game($gameType);
		$game2->level = 2;
		$game2->name = 'Nucleus tagging';
		$game2->instructions = ''
				.'<p>In the image below one or more cells are displayed. </p>'
				.'<p>Draw a square around each nucleus, even if they are lying partly behind other cells/debris. When the nucleus does lie partly behind other cells and this is making two or more nuclei overlap, you may draw overlapping squares.</p>'
				.'<p>Do not count nuclei that are less then 50% visible</p>'
				.'<p>'
				.'Examples:'
				.'</p>'
				.'<img src="img/NuclEx_instructions.png">';
		$game2->extraInfo = serialize([ 'label' => 'There are no nuclei in this image' ]);
		$game2->save();
		
		$images = glob('public/img/cellExAndNuclEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: NuclEx with image: '.$image);
			$data = $image;
			$task = new Task($game2, $data);
			$task->save();
		}
		
		$game3 = new Game($gameType);
		$game3->level = 3;
		$game3->name = 'Colony tagging';
		$game3->instructions = ''
				.'<p>In the image below one or more agar colonies are displayed. </p>'
				.'<p>Draw a square around each colony, even if they are touching other colonies. </p>'
				.'<p>When the colony touches other colonies, you may draw overlapping squares, as long as you know for sure that the colony you tagged is in fact ONE colony.</p>'
				.'<p>Do not count colonies that are less then 50% visible</p>'
				.'<p>'
				.'Examples:'
				.'</p>'
				.'<img src="img/ColEx_instructions.png">';
		$game3->extraInfo = serialize([ 'label' => 'There are no colonies in this image' ]);
		$game3->save();
		
		$images = glob('public/img/colEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: ColEx with image: '.$image);
			$data = $image;
			$task = new Task($game3, $data);
			$task->save();
		}
		
		
		
		$this->command->info('Create test VesExGameType');
		$gameType = new GameType(new VesExGameType());
		$gameType->save();
		
		$game4 = new Game($gameType);
		$game4->level = 1;
		$game4->name = 'Vesicle locating';
		$game4->instructions = ''
				.'<p>In the image below, one or more cells which contain vesicles are displayed. </p>'
				.'<p>If there is more then one cell completely visible, we want you to annotate the cell with the red border around it. </p>'
				.'<p>In this image, the vesicles appear brighter then the rest. </p>'
				.'<p>Tick the boxes below the image if the statement is true. </p>'
				.'<p>Pay attention! The second and third statements are uninteresting when the first statement is true, so they will disappear when the first staement is ticked. </p>'
				.'<p>'
				.'Example:'
				.'</p>'
				.'<img src="img/VesEx_instructions.png">';
		$game4->extraInfo = serialize([ 	'label' => 'There are no vesicles in this image', 
										'label1' => 'The vesicles are equally distributed', 
										'label2' => 'The vesicles are near the tip' , 
										'label3' => 'The vesicles are near the nucleus']);
		$game4->save();
		
		$images = glob('public/img/vesEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: VesEx with image: '.$image);
			$data = $image;
			$task = new Task($game4, $data);
			$task->save();
		}*/
		
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
		
		$this->command->info('Create test CampaignType');
		$campaignType = new CampaignType(new QuantityCampaignType());
		$campaignType->save();
		
		$this->command->info('Create test Campaign');
		$campaign = new Campaign($campaignType);
		$campaign->name = 'Army Mission';
		$campaign->description = '<p>In this campaign you will be working for the army. </p>';
		$campaign->image = 'img/army_mission.png';
		$campaign->campaign_type_id = $campaignType->id;
		$campaign->save();
		
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames();
		$campaign_games->campaign_id = $campaign->id;
		$campaign_games->game_id = $game1->id;
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames();
		$campaign_games->campaign_id = $campaign->id;
		$campaign_games->game_id = $game1->id;
		$campaign_games->save();
		
		/*$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames();
		$campaign_games->campaign_id = $campaign->id;
		$campaign_games->game_id = $game2->id;
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames();
		$campaign_games->campaign_id = $campaign->id;
		$campaign_games->game_id = $game3->id;
		$campaign_games->save();*/
	}
}