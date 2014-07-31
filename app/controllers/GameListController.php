<?php
class GameListController extends GameController {

	public function listGames() {
		// LOAD ALL THESE FROM DATABASE , depending on what user has already done.
		$level0 = array(
				'number' => 0,
				'items'  => array(
						array(
								'link' => 'playGame?game=FactorSpanController',
								'image' => 'img/factorspan.png',
								'text' => 'Factor span',
								'enabled' => true
						),
						array(
								'link' => 'factspan_contraction.html',
								'image' => 'img/factor_validation1.png',
								'text' => 'Factor validation',
								'enabled' => true
						)
						
				)
		);
		$level1 = array(
				'number' => 1,
				'items'  => array(
						array(
								'link' => 'img/factorspan.png',
								'text' => 'Factorspan II',
								'image' => 'img/factorspan.png',
								'enabled' => true
						),
						array(
								'link' => 'img/factor_validation1.png',
								'text' => 'Factor validation II',
								'image' => 'img/factor_validation1.png',
								'enabled' => true
						),
						array(
								'link' => 'reldir.html',
								'image' => 'img/relation_direction.png',
								'text' => 'Relation direction',
								'enabled' => true
						),
						array(
								'link' => 'relex.html',
								'image' => 'img/relation_extraction.png',
								'text' => 'Relation extraction',
								'enabled' => true
						)

				)
		);
		$level2 = array(
				'number' => 2,
				'items'  => array(
						array(
								'link' => 'img/factorspan.png',
								'text' => 'Factorspan III',
								'image' => 'img/factorspan.png',
								'enabled' => true
						),
						array(
								'link' => 'img/factor_validation1.png',
								'text' => 'Factor validation III',
								'image' => 'img/factor_validation1.png',
								'enabled' => true
						),
						array(
								'link' => 'relex.html',
								'image' => 'img/relation_direction.png',
								'text' => 'Relation direction II',
								'enabled' => true
						),
						array(
								'link' => 'relex.html',
								'image' => 'img/relation_extraction.png',
								'text' => 'Relation extraction II',
								'enabled' => true
						),
						array(
								'link' => 'passage_alignment.html',
								'image' => 'img/passage_alignment.png',
								'text' => 'Passage alignment',
								'enabled' => true
						),
						array(
								'link' => 'question_answer.html',
								'image' => 'img/question_answer.png',
								'text' => 'Question answer',
								'enabled' => true
						)
				)
		);
		$level3 = array(
				'number' => 3,
				'items'  => array(
						array(
								'link' => '',
								'text' => 'Factorspan IV',
								'image' => 'img/factorspan.png',
								'enabled' => true
						),
						array(
								'link' => '',
								'text' => 'Factor validation IV',
								'image' => 'img/factor_validation1.png',
								'enabled' => true
						),
						array(
								'link' => '',
								'image' => 'img/relation_direction.png',
								'text' => 'Relation direction III',
								'enabled' => true
						),
						array(
								'link' => '',
								'image' => 'img/relation_extraction.png',
								'text' => 'Relation extraction III',
								'enabled' => true
						),
						array(
								'link' => '',
								'image' => 'img/passage_alignment.png',
								'text' => 'Passage alignment II',
								'enabled' => true
						),
						array(
								'link' => '',
								'image' => 'img/question_answer.png',
								'text' => 'Question answer II',
								'enabled' => true
						)
				)
		);
		$level4 = array(
				'number' => 4,
				'items'  => array(
						array(
								'link' => '',
								'image' => 'img/relation_direction.png',
								'text' => 'Relation direction IV',
								'enabled' => false
						),
						array(
								'link' => '',
								'image' => 'img/relation_extraction.png',
								'text' => 'Relation extraction IV',
								'enabled' => false
						),
						array(
								'link' => '',
								'image' => 'img/passage_alignment.png',
								'text' => 'Passage alignment III',
								'enabled' => false
						),
						array(
								'link' => '',
								'image' => 'img/question_answer.png',
								'text' => 'Question answer III',
								'enabled' => false
						)
				)
		);
		$level5 = array(
				'number' => 5,
				'items'  => array(
						array(
								'link' => '',
								'image' => 'img/passage_alignment.png',
								'text' => 'Passage alignment IV',
								'enabled' => false
						),
						array(
								'link' => '',
								'image' => 'img/question_answer.png',
								'text' => 'Question answer IV',
								'enabled' => false
						)
				)
		);
		$level6 = array(
				'number' => 6,
				'items'  => array(
						array(
								'link' => '',
								'image' => 'img/challenging_mode.png',
								'text' => 'Challenging mode',
								'enabled' => false
						),
						array(
								'link' => '',
								'image' => 'img/survival_mode.png',
								'text' => 'Survival mode',
								'enabled' => false
						)
				)
		);

		$levels = array($level0, $level1, $level2, $level3, $level4, $level5, $level6);
		
		return View::make('gameMenu')->with('levels', $levels);
	}
}
