<?php
class CellExTaskType extends TaskTypeHandler {

	public function getName() {
		return 'CellEx';
	}
	
	public function getDescription() {
		return 'Extracting cells from microscopic images';
	}
	
	public function getDataDiv() {
		$divHTML = "";
		$divHTML .= "<label for='data' class='col-sm-2 control-label'>Image URL:</label>";
		$divHTML .= "	<div class='col-xs-3'>";
		$divHTML .= "		<input class='form-control' name='cellExImage' type='text' value='' id='cellExImage'>";
		$divHTML .= "	</div>";
		return $divHTML;
	}

	public function parseInputs($inputs) {
		return $inputs['cellExImage'];
	}
}
