<?php
class DummyGameType extends GameTypeHandler {

	public function getName() {
		return 'Dummy';
	}
	
	public function getDescription() {
		return 'Not a real task, just for devel debugging and stuff...';
	}
	
	public function getExtrasDiv($extraInfo) {
		return 'No DIV here, sorry :-P';
	}
	
	public function parseExtraInfo($inputs) {
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
	
	public function renderTask($task) {
		return $task->data;
	}
	
	public function validateData($data) {
		return true;
	}
}
