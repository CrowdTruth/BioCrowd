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
		
		$this->createTestUsers();
		
		$this->createOtherGames();
		
		$this->createCampaigns();
		
		$this->createLevels();
	}
	
	public function createOtherGames() {
		$this->command->info('Create test CellExGameType');
		$gameType = new GameType(new CellExGameType());
		$gameType->save();
		
		$game1 = new Game($gameType);
		$game1->level = 1;
		$game1->name = 'Cell tagging';
		$game1->instructions = ''
				.'<p>In the image below one or more cells are displayed. </p>'
				.'<p>Please mark all cells in one of the following ways: </p>'
				.'<p>Way 1. With your mouse, drag and draw a shape around each cell, even if they are lying partly behind other cells/debris. When two cells are overlapping, you may draw overlapping shapes.</p>'
				.'<p>Way 2. Click on the CENTER of each cell you want to mark. </p>'
				.'<p>You can mix these 2 ways if you want. </p>'
				.'<p>Do not count cells that are less then 50% visible</p>'
				.'<p>'
				.'Examples:'
				.'</p>'
				.'<img src="img/CellEx_instructions.png">';
		$game1->extraInfo = serialize([ 'label' => 'Mark each cell by clicking it or drawing a shape around it',
				'label1' => 'I have annotated all cells', 
				'label2' => 'There were too many cells to annotate',
				'label3' => 'No cell visible',
				'label4' => 'Other',
				
				'label5' => 'Enter the total number of cells here:',

				'label6' => 'Good',
				'label7' => 'Medium',
				'label8' => 'Poor',
				'label9' => 'Blank (Black) Image',
				'label10' => 'No Image',
				]);
		$game1->score = '10';
		$game1->save();
		
		$this->command->info('Create test TaskType');
		$taskType = new TaskType(new CellExTaskType());
		$taskType->save();
		
		$images = glob('public/img/cellExAndNuclEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test CellExTask with image: '.$image);
			$data = $image;
			$task = new Task($taskType, $data);
			$task->save();
			//Fill the GameHasTask table accordingly
			$this->command->info('Create test GameHasTask');
			$gameHasTask = new GameHasTask($game1, $task);
			$gameHasTask->save();
		}
		
		$game2 = new Game($gameType);
		$game2->level = 2;
		$game2->name = 'Nucleus tagging';
		$game2->instructions = ''
				.'<p>In the image below one or more cells are displayed. </p>'
				.'<p>Please mark all cells in one of the following ways: </p>'
				.'<p>Way 1. With your mouse, drag and draw a shape around each nucleus, even if they are lying partly behind other cells/debris. 
						When the nucleus does lie partly behind other cells and this is making two or more nuclei overlap, you may draw overlapping shapes.</p>'
				.'<p>Way 2. Click on the CENTER of each nucleus you want to mark. </p>'
				.'<p>You can mix these 2 ways if you want. </p>'
				.'<p>Do not count nuclei that are less then 50% visible</p>'
				.'<p>'
				.'Examples:'
				.'</p>'
				.'<img src="img/NuclEx_instructions.png">';
		$game2->extraInfo = serialize([ 'label' => 'Mark each nucleus by clicking it or drawing a shape around it',
				'label1' => 'I have annotated all nuclei', 
				'label2' => 'There were too many nuclei to annotate',
				'label3' => 'No nuclei visible',
				'label4' => 'Other',
				
				'label5' => 'Enter the total number of nuclei here:',

				'label6' => 'Good',
				'label7' => 'Medium',
				'label8' => 'Poor',
				'label9' => 'Blank (Black) Image',
				'label10' => 'No Image',
				]);
		$game2->score = '20';
		$game2->save();
		
		$images = glob('public/img/cellExAndNuclEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: NuclEx with image: '.$image);
			$data = $image;
			$task = new Task($taskType, $data);
			$task->save();
			//Fill the GameHasTask table accordingly
			$this->command->info('Create test GameHasTask');
			$gameHasTask = new GameHasTask($game2, $task);
			$gameHasTask->save();
		}
		
		$game3 = new Game($gameType);
		$game3->level = 3;
		$game3->name = 'Colony tagging';
		$game3->instructions = ''
				.'<p>In the image below one or more agar colonies are displayed. </p>'
				.'<p>Please mark all cells in one of the following ways: </p>'
				.'<p>Way 1. With your mouse, drag and draw a shape around each colony, even if they are touching other colonies. 
						When the colony touches other colonies, you may draw overlapping shapes, as long as you know for sure that the colony you tagged is in fact ONE colony.</p>'
				.'<p>Way 2. Click on the CENTER of each colony you want to mark. </p>'
				.'<p>You can mix these 2 ways if you want. </p>'
				.'<p>Do not count colonies that are less then 50% visible</p>'
				.'<p>'
				.'Examples:'
				.'</p>'
				.'<img src="img/ColEx_instructions.png">';
		$game3->extraInfo = serialize([ 'label' => 'Mark each colony by clicking it or drawing a shape around it',
				'label1' => 'I have annotated all colonies', 
				'label2' => 'There were too many colonies to annotate',
				'label3' => 'No colonies visible',
				'label4' => 'Other',
				
				'label5' => 'Enter the total number of colonies here:',
				]);
		$game3->score = '30';
		$game3->save();
		
		$images = glob('public/img/colEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: ColEx with image: '.$image);
			$data = $image;
			$task = new Task($taskType, $data);
			$task->save();
			//Fill the GameHasTask table accordingly
			$this->command->info('Create test GameHasTask');
			$gameHasTask = new GameHasTask($game3, $task);
			$gameHasTask->save();
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
				.'<p>VESICLES can be seen as tiny dots present throughout (a part of) the cell.</p>'
				.'<p>VESICLES can be seen as larger "clumps" of color, varying in size.</p>'
				.'<p>Different VESICLES can exhibit different behavior we will call TRENDS.</p>'
				.'<p>Some images will have VESICLES with different fluorescent colors.</p>'
				.'<p>Refer to the rest of the image for correct identification of the cells and to be sure of not taking background spots as Vesicles.</p>'
				.'<p>'
				.'Example:'
				.'</p>'
				.'<img src="img/VesEx_instructions.png">';
		$game4->extraInfo = serialize([	'label' => 'Click on the icon below which best describes the VESICLE location', 
										'label1' => 'Side Nucleus', 
										'label2' => 'Ring around Nucleus' , 
										'label3' => 'My selection applies to All the VESICLES in this image',
										'label4' => 'One or more CELLS in this image contained VESICLES which behaved differently than my selection',
										'label5' => 'No CELL visible'
										]);
		$game4->score = '10';
		$game4->save();
		
		$this->command->info('Create test TaskType');
		$taskType = new TaskType(new VesExTaskType());
		$taskType->save();
		
		$images = glob('public/img/vesEx/*');
		foreach($images as $image){
			$image = substr($image,7);
			$this->command->info('Create test Game: VesEx with image: '.$image);
			$data = $image;
			$task = new Task($taskType, $data);
			$task->save();
			//Fill the GameHasTask table accordingly
			$this->command->info('Create test GameHasTask');
			$gameHasTask = new GameHasTask($game4, $task);
			$gameHasTask->save();
		}
	}
	
	public function createCampaigns() {
		$game1 = Game::where('name', '=', 'Cell tagging')->first();
		$game2 = Game::where('name', '=', 'Nucleus tagging')->first();
		$game3 = Game::where('name', '=', 'Colony tagging')->first();
		$game4 = Game::where('name', '=', 'Vesicle locating')->first();
		
		$this->command->info('Create test StoryCampaignType');
		$campaignType = new CampaignType(new StoryCampaignType());
		$campaignType->save();
		
		$this->command->info('Create test Campaign');
		$campaign = new Campaign($campaignType);
		$campaign->name = 'StoryCampaignType';
		$campaign->badgeName = 'StoryCampaignType';
		$campaign->description = '<p>In this campaign you will be working for the army. </p>';
		$campaign->image = 'img/army_mission.png';
		$campaign->score = '100';
		$campaign->save();
		
		$this->command->info('Create test Story');
		$story1 = new Story($campaign);
		$story1->story_string = '<p>The enemy has a small base in the desert. It is very important to estimate the amount of troops the enemy has in that base.
						In order to do this, you must count the amount of tents/buildings the enemy has built there. Be careful! The enemy has
						tried to hide their buildings by putting them in the awning of the walls. Good luck!</p>';
		$story1->extraInfo = serialize([ 'label' => 'Mark each building by clicking it or drawing a shape around it',
				'label1' => 'I have annotated all buildings',
				'label2' => 'There were too many buildings to annotate',
				'label3' => 'There are no tents/buildings in this image',
				'label4' => 'Other',
		
				'label5' => 'Enter the total number of buildings here:'
				]);
		$story1->save();
		
		$this->command->info('Create test Story');
		$story2 = new Story($campaign);
		$story2->story_string = '<p>The building count has given us a good sense of their numbers, well done. Now we need to know the blueprint of
						their main building, where we will begin our assault. We have used a satellite with X-ray vision to see the
						walls of the building. <Example of a room> Put a marking on each room you see. Be careful! Some rooms might lie
						on top of other rooms and seem to overlap in the picture. Count these as two separate rooms. </p>';
		$story2->extraInfo = serialize([ 'label' => 'Mark each room by clicking it or drawing a shape around it',
				'label1' => 'I have annotated all rooms',
				'label2' => 'There were too many rooms to annotate',
				'label3' => 'There are no rooms in this image',
				'label4' => 'Other',
		
				'label5' => 'Enter the total number of rooms here:'
				]);
		$story2->save();
		
		$this->command->info('Create test Story');
		$story3 = new Story($campaign);
		$story3->story_string = '<p>The blueprint you constructed in last assignment has given us a new insight: the people that are standing in the rooms can
						be seen in the x-ray photos as well! This would give us an even more accurate count of the amount of enemies in the base.
						<Example of an enemy head> Put a marking on each enemy you see. Be careful! Some enemies might seem to overlap since
						they are on different stories of the building. Count these as two separate enemies. </p>';
		$story3->extraInfo = serialize([ 'label' => 'Mark each enemy by clicking it or drawing a shape around it',
				'label1' => 'I have annotated all enemies',
				'label2' => 'There were too many enemies to annotate',
				'label3' => 'There are no enemies in this image',
				'label4' => 'Other',
		
				'label5' => 'Enter the total number of rooms here:'
				]);
		$story3->save();
		
		$this->command->info('Create test Story');
		$story4 = new Story($campaign);
		$story4->story_string = '<p>Oh dear. Some people have made mistakes in marking the enemies. However, we don\'t know who made the mistakes. We
						would like you to go over this marking document and add any enemies that haven\'t been marked yet and delete any
						false markings. Keep in mind that we don\'t know who made the mistakes, so you might not find any mistakes! </p>';
		$story4->extraInfo = serialize([ 'label' => 'Mark each enemy by clicking it or drawing a shape around it',
				'label1' => 'I have annotated all mistakes',
				'label2' => 'There were too many mistakes to annotate',
				'label3' => 'There are no mistakes in this image',
				'label4' => 'Other',
				
				'label5' => 'Enter the total number of enemies here:'
				]);
		$story4->save();
		
		$this->command->info('Create test CampaignStories');
		$campaign_stories = new CampaignStories($campaign, $story1);
		$campaign_stories->save();
		
		$this->command->info('Create test CampaignStories');
		$campaign_stories = new CampaignStories($campaign, $story2);
		$campaign_stories->save();
		
		$this->command->info('Create test CampaignStories');
		$campaign_stories = new CampaignStories($campaign, $story3);
		$campaign_stories->save();
		
		$this->command->info('Create test CampaignStories');
		$campaign_stories = new CampaignStories($campaign, $story4);
		$campaign_stories->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames($campaign, $game1);
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames($campaign, $game2);
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames($campaign, $game3);
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames($campaign, $game4);
		$campaign_games->save();
		
		$this->command->info('Create test QuantityCampaignType');
		$campaignType = new CampaignType(new QuantityCampaignType());
		$campaignType->save();
		
		$this->command->info('Create test Campaign');
		$campaign = new Campaign($campaignType);
		$campaign->name = 'QuantityCampaignType';
		$campaign->badgeName = 'QuantityCampaignType';
		$campaign->description = '<p>In this campaign you will do as many games as possible. </p>';
		$campaign->image = 'img/army_mission.png';
		$campaign->score = '100';
		$campaign->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames($campaign, $game1);
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames($campaign, $game2);
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames($campaign, $game3);
		$campaign_games->save();
		
		$this->command->info('Create test CampaignGames');
		$campaign_games = new CampaignGames($campaign, $game4);
		$campaign_games->save();		
	}
	
	public function createTestUsers() {
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
		
		User::create( [
		'email' => 'veltkamp.w.isc@nl.ibm.com',
		'name' => 'Wouter',
		'level' => '4',
		'title' => 'Padawan',
		'password' => Hash::make('Wouter')
		] );
		
		User::create( [
		'email' => 'tessa.mulderISC@nl.ibm.com',
		'name' => 'Tessa',
		'level' => '4',
		'title' => 'Padawan',
		'password' => Hash::make('Tessa')
		] );
	}
	
	public function createLevels() {
		$this->command->info('Create test Levels');
		$level1 = new Level();
		$level1->level = 1;
		$level1->max_score = 200;
		$level1->save();
		
		$level2 = new Level();
		$level2->level = 2;
		$level2->max_score = 450;
		$level2->save();
		
		$level3 = new Level();
		$level3->level = 3;
		$level3->max_score = 600;
		$level3->save();
		
		$level4 = new Level();
		$level4->level = 4;
		$level4->max_score = 800;
		$level4->save();
	}
}
