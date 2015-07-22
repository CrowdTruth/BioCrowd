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
		
		// Install games separately ?
		$controller = new GameAdminController;
		$controller->installGameType('CellExGameType');
		$controller->installGameType('VesExGameType');

		$this->createGames();
		// Install campaign types separately ?
		$campaignType = new CampaignType(new StoryCampaignType());
		$campaignType->save();
		$campaignType = new CampaignType(new QuantityCampaignType());
		$campaignType->save();
		
		 $this->createCampaigns();
		$this->createLevels();
	}

	// TODO: unused -- remove.
	public function createGames() {
		$this->command->info('Create test CellExGameType');
		$gameType = GameType::where('name', '=', 'CellEx')->first();
		
		$game1 = new Game($gameType);
		$game1->level = 1;
		$game1->name = 'Fluorescent Cells (Green)';
		$game1->instructions = ''
				.'<p>You are given 3 microscopic images of one or more human cells. You will be identifying the cells in the images. Don\'t worry, it\'s easier than you think. Take a look at these examples. <button class="openExamples" style="margin-top: 50px; margin-bottom:50px;">Show Examples</button></p>'
				.'';
		$game1->examples = ''
				.'<div style="background-color:#6495ed; padding: 0px 20px 10px 20px;">'
				.'<div style="font-size: 20pt; color:#000;">Examples:</div>'
				.'</p>'
				.'<div style="background-color:#FFF; border:1px solid #000; padding:10px;">'
				.'<div style="border:1px solid #000;"><font color="#DC18DA"><b>Purple dots</b></font> show correctly counted cells. <font color="#3C5825"><b>Count & annotate a cell only when at least half of it is visible. </b></font><br>
						<font color="#DC18DA"><b>Mark</b></font> each cell as close to its center as possible. <br>
						(When you cannot see the complete cell, mark where you <i>think</i> the center would be.)<br> 
						<br>
						<font color="#0906C5"><b>Example A</b></font>. You see <font color="#3C5825"><b>1 complete cell, 1 nearly complete cell</b></font>, and 1 small bit of a cell => <font color="#DC18DA"><b>2 purple dot</b></font> markings. <br>
						<font color="#0906C5"><b>Example B</b></font>. You see <font color="#3C5825"><b>2 complete cells</b></font>, more then half of <font color="#3C5825"><b>1 cell</b></font>, and a little bit of 2 other cells => <font color="#DC18DA"><b>3 purple dot</b></font> markings. <br>
						<br>
						The <font color="#0906C5">dotted blue line</font> shows the imaginary outline of the cell beyond the border of the image.</div>'
				.'<img width="100%" src="img/CellTagging_Green.png">'
				.'</div>'
				.'</div>'
				.'';
		$game1->steps = ''
				.'<div style="font-size: 20pt; color:#000;">Cell identification steps</div>'
				.'<b><p>Step 1: Click near the Center of al visible cells or drag the mouse to draw a box around the cells. Keep a mental count while you are tagging. </p>'
				.'<p>Step 2: Choose the option from the given list which best describes your cell markings. </p>'
				.'<p>Step 3: Report the number of cells you have counted in the designated field. </p>'
				.'<p>Step 4: Click the button which best describes the given image quality. </p></b>'
				.'<br>'
				.'Detailed explanation for Step 1: '
				.'<div>'
				.'<div class="col span_2_of_8"><img src="img/annotationMenu.png"></div>'
				.'<div class="col span_6_of_8"><ul style="-webkit-margin-before: 0em;-webkit-margin-after: 0em;">'
				.'<li>This menu is located to the left of (or above) your task images. </li>'
				.'<li>The "Drawn" table keeps track of your markings. (mouse clicks or drags)</li>'
				.'<li>To remove the last cell marking, click the <img src=img/removeLastButton.png> button.</li>'
				.'This removes the markings in the reversed order in which they were made (last mark will be deleted first). '
				.'<li>To undo a <i>specific</i> annotation click the <b>x</b> next to the faulty mark in the "Drawn" table which you want to remove. </li>'
				.'<li>Once you are happy with the result, click the "Next question" button. </li>'
				.'</ul></div>'
				.'</div>'
				.'';
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
		$game2->name = 'Dark Nuclei (Green)';
		$game2->instructions = ''
				.'<p>You are given 3 microscopic images of one or more human cells. You will be identifying the nuclei in the images. Don\'t worry, it\'s easier than you think. Take a look at these examples. <button class="openExamples" style="margin-top: 50px; margin-bottom:50px;">Show Examples</button></p>'
				.'';
		$game2->examples = ''
				.'<div style="background-color:#6495ed; padding: 0px 20px 10px 20px;">'
				.'<div style="font-size: 20pt; color:#000;">Examples:</div>'
				.'</p>'
				.'<div style="background-color:#FFF; border:1px solid #000; padding:10px;">'
				.'<div style="border:1px solid #000;"><font color="#DC18DA"><b>Purple dots</b></font> show correctly counted nuclei. <font color="#3C5825"><b>Count & annotate nuclei only when at least half of them is visible. </b></font><br>
						<font color="#DC18DA"><b>Mark</b></font> each nucleus as close to its center as possible. <br>
						(When you cannot see the complete nucleus, mark where you <i>think</i> the center would be.)<br> 
						<br>
						<font color="#0906C5"><b>Example A</b></font>. You see <font color="#3C5825"><b>2 cells, 2 nuclei</b></font> => <font color="#DC18DA"><b>2 purple dot</b></font> markings. <br>
						<font color="#0906C5"><b>Example B</b></font>. You see <font color="#3C5825"><b>1 cell, 2 nuclei</b></font> => <font color="#DC18DA"><b>2 purple dot</b></font> markings. <br>
						<font color="#0906C5"><b>Example C</b></font>. You see <font color="#3C5825"><b>2 partially visible cells, 2 more then half visible nuclei</b></font> => <font color="#DC18DA"><b>2 purple dot</b></font> markings. <br>
						<br>
						The <font color="#0906C5">dotted blue line</font> shows the imaginary outline of the nucleus beyond the border of the image. </div>'
				.'<img width="100%" src="img/NucleusTagging_Green.png">'
				.'</div>'
				.'</div>'
				.'';
		$game2->steps = ''
				.'<div style="font-size: 20pt; color:#000;">Nuclei identification steps</div>'
				.'<p>Step 1: Click near the Center of al visible nuclei or drag the mouse to draw a box around the nuclei. Keep a mental count while you are tagging. </p>'
				.'<p>Step 2: Choose the option from the given list which best describes your cell markings. </p>'
				.'<p>Step 3: Report the number of nuclei you have counted in the designated field. </p>'
				.'<p>Step 4: Click the button which best describes the given image quality. </p>'
				.'<br>'
				.'Detailed explanation for Step 1: '
				.'<div>'
				.'<div class="col span_2_of_8"><img src="img/annotationMenu.png"></div>'
				.'<div class="col span_6_of_8"><ul style="-webkit-margin-before: 0em;-webkit-margin-after: 0em;">'
				.'<li>This menu is located to the left of (or above) your task images. </li>'
				.'<li>The "Drawn" table keeps track of your markings. (mouse clicks or drags)</li>'
				.'<li>To remove the last nucleus marking, click the <img src=img/removeLastButton.png> button.</li>'
				.'This removes the markings in the reversed order in which they were made (last mark will be deleted first). '
				.'<li>To undo a <i>specific</i> annotation click the <b>x</b> next to the faulty mark in the "Drawn" table which you want to remove. </li>'
				.'<li>Once you are happy with the result, click the "Next question" button. </li>'
				.'</ul></div>'
				.'</div>'
				.'';
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
		$game3->name = 'Agar Contamination';
		$game3->instructions = ''
				.'<p>You are given 3 microscopic images of one or more colonies. You will be identifying the colonies in the images. Don\'t worry, it\'s easier than you think. Take a look at these examples. <button class="openExamples" style="margin-top: 50px; margin-bottom:50px;">Show Examples</button></p>'
				.'';
		$game3->examples = ''
				.'<div style="background-color:#6495ed; padding: 0px 20px 10px 20px;">'
				.'<div style="font-size: 20pt; color:#000;">Examples:</div>'
				.'</p>'
				.'<div style="background-color:#FFF; border:1px solid #000; padding:10px;">'
				.'<div style="border:1px solid #000;"><font color="#DC18DA"><b>Purple dots</b></font> show correctly counted colonies. <font color="#3C5825"><b>Count & annotate a colony only when at least half of it is visible. </b></font><br>
						<font color="#DC18DA"><b>Mark</b></font> each colony as close to its center as possible. <br>
						(When you cannot see the complete colony, mark where you <i>think</i> the center would be.)<br>
						Do not mark colonies that have merged into one big clump. <br>
						Do mark colonies that are at the edge of of the petri dish of which you can see more then half of the colony present. <br>
						<br>
						<font color="#0906C5"><b>Example A</b></font>. You see <font color="#3C5825"><b>13 complete colonies</b></font>, 2 clumps of merged colonies, and a few line shaped clumps at the bottom => <font color="#DC18DA"><b>13 purple dot</b></font> markings. <br>
						<font color="#0906C5"><b>Example B</b></font>. You see <font color="#3C5825"><b>2 complete colonies</b></font>, more then half of <font color="#3C5825"><b>1 colony</b></font>, a little bit of 2 other colony, and <font color="#3C5825"><b>1 colony at the edge of the petri dish</b></font> => <font color="#DC18DA"><b>4 purple dot</b></font> markings. <br>
						<br>
						The <font color="#0906C5">dotted blue line</font> shows the imaginary outline of the colony beyond the border of the image.</div>'
				.'<img width="100%" src="img/ColonyTagging_Mixed.png">'
				.'</div>'
				.'</div>'
				.'';
		$game3->steps = ''
				.'<div style="font-size: 20pt; color:#000;">Colony identification steps</div>'
				.'<p>Step 1: Click near the Center of al visible colonies or drag the mouse to draw a box around the colonies. Keep a mental count while you are tagging. </p>'
				.'<p>Step 2: Choose the option from the given list which best describes your colony markings. </p>'
				.'<p>Step 3: Report the number of colonies you have counted in the designated field. </p>'
				.'<p>Step 4: Click the button which best describes the given image quality. </p>'
				.'<br>'
				.'Detailed explanation for Step 1: '
				.'<div>'
				.'<div class="col span_2_of_8"><img src="img/annotationMenu.png"></div>'
				.'<div class="col span_6_of_8"><ul style="-webkit-margin-before: 0em;-webkit-margin-after: 0em;">'
				.'<li>This menu is located to the left of (or above) your task images. </li>'
				.'<li>The "Drawn" table keeps track of your markings. (mouse clicks or drags)</li>'
				.'<li>To remove the last colony marking, click the <img src=img/removeLastButton.png> button.</li>'
				.'This removes the markings in the reversed order in which they were made (last mark will be deleted first). '
				.'<li>To undo a <i>specific</i> annotation click the <b>x</b> next to the faulty mark in the "Drawn" table which you want to remove. </li>'
				.'<li>Once you are happy with the result, click the "Next question" button. </li>'
				.'</ul></div>'
				.'</div>'
				.'';
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
		$gameType = GameType::where('name', '=', 'VesEx')->first();
		
		$game4 = new Game($gameType);
		$game4->level = 1;
		$game4->name = 'Glowing Vesicles (Green)';
		$game4->instructions = ''
				.'<p>You are given 3 microscopic images of one or more human cells. You will be identifying the vesicles in the images. Don\'t worry, it\'s easier than you think. Take a look at these examples. <button class="openExamples" style="margin-top: 50px; margin-bottom:50px;">Show Examples</button></p>'
				.'';
		$game4->examples = ''
				.'<div style="background-color:#6495ed; padding: 0px 20px 10px 20px;">'
				.'<div style="font-size: 20pt; color:#000;">Examples:</div>'
				.'</p>'
				.'<div style="background-color:#FFF; border:1px solid #000; padding:10px;">'
				.'<div style="border:1px solid #000;">Arrows point to the area the example is classifying. A few of the larger nuclei have been marked with a <i>star</i> to help identify them. <br>
						<font color="#0906C5"><b>Example A</b></font>. Vesicles at the <i>tip</i> of the cell. <br>
						<font color="#0906C5"><b>Example B</b></font>. Vesicles fairly <i>evenly</i> diffused throughout the cell (think of a mist or fog)<br>
						<font color="#0906C5"><b>Example C</b></font>. Vesicles on <i>one</i> side of the nucleus. <br>
						<font color="#0906C5"><b>Example D</b></font>. Vesicles in <i>clusters</i>(clumps of small dots stuck together). <br>
						<font color="#0906C5"><b>Example E</b></font>. Vesicles in a <i>ring</i> around the nucleus. <br>
						</div>'
				.'<img width="100%" src="img/Vesicle_Locating_Mixed.png">'
				.'</div>'
				.'</div>'
				.'';
		$game4->steps = ''
				.'<div style="font-size: 20pt; color:#000;">Vesicle identification steps</div>'
				.'<p>Step 1: First, take a look at the image and click on the icon which best describes the behavior of the vesicles.</p>'
				.'<ul>'
				.'<li>Vesicles can be seen as tiny dots present throughout (a part of) the cell.</li>'
				.'<li>Vesicles can be seen as larger "clumps" of color, varying in size.</li>'
				.'<li>Different vesicles can exhibit different behavior we will call trends.</li>'
				.'<li>Some images will have vesicles with different fluorescent colors.</li>'
				.'<li>Refer to the rest of the image for correct identification of the cells and to be sure of not taking background spots as vesicles.</li>'
				.'</ul>'
				.'<p>Step 2: Choose the option from the given list which best describes your CELL markings.</p>'
				.'<p>Step 3: Report the number of cells you have counted in the designated field. </p>'
				.'<p>Step 4: Click the button which best describes the given image quality. </p>'
				.'';
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
	
	// TODO: unused -- remove ?
	public function createCampaigns() {
		$game1 = Game::find(1);
		$game2 = Game::find(2);
		$game3 = Game::find(3);
		$game4 = Game::find(4);
		
		$this->command->info('Create test StoryCampaignType');
		$campaignType = CampaignType::where('name', '=', 'Story')->first();
		
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
		$campaignType = CampaignType::where('name', '=', 'Quantity')->first();
		
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
			'score' => '999',
			'cellBioExpertise' => 'none',
			'expertise' => 'software development'
		] );
		
		User::create( [
			'email' => 'loeloe87@hotmail.com',
			'name' => 'Merel',
			'password' => Hash::make('Merel'),
			'level' => '3',
			'title' => 'White belt',
			'score' => '460',
			'cellBioExpertise' => 'uniBachelor',
			'expertise' => 'BioInformatics master student'
		] );
		
		User::create( [
			'email' => 'veltkamp.w.isc@nl.ibm.com',
			'name' => 'Wouter',
			'level' => '4',
			'title' => 'Yellow belt',
			'password' => Hash::make('Wouter'),
			'cellBioExpertise' => 'none',
			'expertise' => 'Front end designer and developer'
		] );
		
		User::create( [
			'email' => 'tessa.mulderISC@nl.ibm.com',
			'name' => 'Tessa',
			'level' => '4',
			'title' => 'Yellow belt',
			'password' => Hash::make('Tessa'),
			'cellBioExpertise' => 'none',
			'expertise' => 'Front end designer'
		] );
	}
	
	public function createLevels() {
		$this->command->info('Create test Levels');
		$level1 = new Level();
		$level1->level = 1;
		$level1->max_score = 200;
		$level1->title = 'Novice';
		$level1->save();
		
		$level2 = new Level();
		$level2->level = 2;
		$level2->max_score = 450;
		$level2->title = 'Padawan';
		$level2->save();
		
		$level3 = new Level();
		$level3->level = 3;
		$level3->max_score = 600;
		$level3->title = 'White belt';
		$level3->save();
		
		$level4 = new Level();
		$level4->level = 4;
		$level4->max_score = 800;
		$level4->title = 'Yellow belt';
		$level4->save();
	}
}
