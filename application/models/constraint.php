<?php

class Constraint extends Eloquent {

	private static $cache_timeout = 10;
	//public static $key = 'group';

	public static function get_constraint($group) {
		return 	Cache::remember (
			$group,
			function() use($group) { return Constraint::find($group); },
			self::$cache_timeout
		);
	}

	public static function get_issues($model, $ignore = array()) {
		$rules = self::get_constraint($model->table())
			? self::get_constraint($model->table())
			: $model::get_default_rules();

		foreach($ignore as $id) {
			unset($rules[$id]);
		}
		$hidden = $model::get_hidden();
		$model::set_hidden(array());

		$validation = Validator::make($model->to_array(), $rules);
		$model::set_hidden($hidden);
		$validation->passes();
		return $validation->errors->all();
	}

}
