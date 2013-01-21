<?php

class User extends Eloquent {

	//public static $key = 'uid';
	public static $hidden = array('password');

	private static $default_rules = array(
		'id' => 'min:1|integer',
		'gid' => 'required|min:1|integer',
		'first_name' => 'required|between:2,20',
		'last_name' => 'required_with:first_name|between:2,80',
		'display_name' => 'required|between:3,30',
		'email' => 'required|email|unique:users',
		'password' => 'required|between:6,128',
	);

	public static function get_default_rules() {
		return self::$default_rules;
	}

	public static function get_hidden() {
		return self::$hidden;
	}

	public static function set_hidden($hidden_value) {
		self::$hidden = $hidden_value;
	}
	
	public function prepare($inputs) {
		$this->id = $inputs['id'];
		$this->gid = $inputs['gid'];
		$this->first_name = $inputs['first_name'];
		$this->last_name = $inputs['last_name'];
		$this->display_name = $inputs['display_name'];
		$this->email = $inputs['email'];
		$this->password = Hash::make($inputs['password']);

		$ignore = $this->id > 0 ? array('email') : null;
		return Constraint::get_issues($this, $ignore);
	}

}
