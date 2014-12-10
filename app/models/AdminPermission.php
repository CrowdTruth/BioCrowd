<?php

class AdminPermission extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'admin_permissions';
	public $timestamps = false;
	
	// Devel note: Add new permissions to filters.php to protect route
	// Also: add new permissions to DatabaseSeeder
	const USERS = 'Users';
	const GAME = 'NewGame';
	const GAMETYPE = 'NewGameType';
	
	public function adminUsers() {
		return $this->belongsToMany('AdminUser');
	}
}
