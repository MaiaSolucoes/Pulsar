<?php

class Permission extends Eloquent {

	private static $cache_timeout = 10;
	public static $key = 'group';

	public static function get_permission($group) {
		return json_decode(
			Cache::remember(
				self::$group,
				function() use($group){ return self::find($group); },
				self::$cache_timeout
			)
		);
	}
}
