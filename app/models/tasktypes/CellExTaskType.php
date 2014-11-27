<?php
class CellExTaskType extends TaskTypeHandler {

	public function getName() {
		return 'CellEx';
	}
	
	public function getDescription() {
		return 'Extracting cells from microscopic images';
	}
}
