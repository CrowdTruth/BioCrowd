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

		/*$this->command->info('Create test CellExGameType');
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
		$game->extraInfo = serialize([ 	'label' => 'There are no vesicles in this image', 
										'label1' => 'The vesicles are equally distributed', 
										'label2' => 'The vesicles are near the tip' , 
										'label3' => 'The vesicles are near the nucleus']);
		$game->save();
		
		$images = glob('public/img/vesEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: VesEx with image: '.$image);
			$data = $image;
			$task = new Task($game, $data);
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
		$campaign_games->game_id = '1';
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames();
		$campaign_games->campaign_id = '1';
		$campaign_games->game_id = '1';
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames();
		$campaign_games->campaign_id = '1';
		$campaign_games->game_id = '2';
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames();
		$campaign_games->campaign_id = '1';
		$campaign_games->game_id = '3';
		$campaign_games->save();
	}
}