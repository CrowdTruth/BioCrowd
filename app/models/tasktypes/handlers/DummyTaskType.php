<?php
/**
 * This TaskTypeHandler does nothing -- this class is mean as an example to illustrate 
 * how to develop your own TaskTypeHandler.
 */
class DummyTaskType extends TaskTypeHandler {
	
	/**
	 * See TaskTypeHandler
	 */
	public function getName() {
		return 'Dummy';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function getDescription() {
		return 'Not a real task, just for devel debugging and stuff...';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function getExtrasDiv($extraInfo) {
		return 'No DIV here, sorry :-P';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function parseExtraInfo($inputs) {
		return 'Should return data ;-)';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function getThumbnail() {
		return 'img/factor_validation1.png';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function getView($task) {
		return View::make('dummytask')
			->with('instructions', $task->instructions)
			->with('gameId', $task->id);
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function processResponse($task) {
		return 'We should do something with your answer...<a href="/gameMenu">home</a>';
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function renderTask($task) {
		return $task->data;
	}
	
	/**
	 * See TaskTypeHandler
	 */
	public function validateData($data) {
		return true;
	}
}
