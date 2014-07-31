<?php
class FactorSpanController {
	// TODO: implement some interface
	// Interface obliges Controller to implement
	//     getView() -> Return view
	//     submitResults()
	
	public static function getView() {
		return View::make('factspan');
	}
	
	public static function submitGame() {
		return 'Factors Span results to be validated and saved';
	}
}
