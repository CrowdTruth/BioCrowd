<?php
class DummyTaskType extends TaskTypeHandler {

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
}
