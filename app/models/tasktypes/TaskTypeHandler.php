<?php
abstract class TaskTypeHandler {
	public abstract function getName();
	public abstract function getDescription();
	public function __toString() {
		return $this->getName();
	}
}
