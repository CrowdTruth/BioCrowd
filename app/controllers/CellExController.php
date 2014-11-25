<?php
class CellExController {
	// TODO: implement some interface
	// Interface obliges Controller to implement
	//     getView() -> Return view
	//     submitResults()
	
	public static function getView() {
		return View::make('cellex');
	}
	
	public static function submitJugement() {
		return 'Cell extraction results to be validated and saved';
	}
}
