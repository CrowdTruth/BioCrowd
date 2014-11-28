<?php

class AdminPermission extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'admin_permissions';
	public $timestamps = false;
	
	public function adminUsers() {
		return $this->belongsToMany('AdminUser');
	}
	
}
