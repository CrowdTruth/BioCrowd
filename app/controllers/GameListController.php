<?php
class GameListController extends GameController {

	public function listGames() {
		// LOAD ALL THESE FROM DATABASE , depending on what user has already done.
		$level1 = array(
				'number' => 1,
				'items'  => array(
						array(
								'link' => 'playGame?game=CellExController',
								'image' => 'img/factor_validation1.png',
								'text' => 'Cell extraction',
								'enabled' => true
						)
						
				)
		);
		$level2 = array(
				'number' => 2,
				'items'  => array(
					
				)
		);
		$level3 = array(
				'number' => 3,
				'items'  => array(
				)
		);
		$level4 = array(
				'number' => 4,
				'items'  => array(
				)
		);
		$level5 = array(
				'number' => 5,
				'items'  => array(
				)
		);
		$level6 = array(
				'number' => 6,
				'items'  => array(
				)
		);

		$levels = array($level1, $level2, $level3, $level4, $level5, $level6);
		
		return View::make('gameMenu')->with('levels', $levels);
	}
}
