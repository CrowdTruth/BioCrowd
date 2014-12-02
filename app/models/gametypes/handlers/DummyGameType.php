<?php
class DummyGameType extends GameTypeHandler {

	public function getName() {
		return 'Dummy';
	}
	
	public function getDescription() {
		return 'Not a real task, just for devel debugging and stuff...';
	}
	
	public function getDataDiv() {
		return 'No DIV here, sorry :-P';
	}
	
	public function parseInputs($inputs) {
		return 'Should return data ;-)';
	}

	public function getThumbnail() {
		return 'img/factor_validation1.png';
	}
	
	public function getView($game) {
		return View::make('dummygame')
			->with('instructions', $game->instructions)
			->with('gameId', $game->id);
	}
	
	public function processResponse($game) {
		return 'We should do something with your answer...<a href="/gameMenu">home</a>';
	}
}
