<?php

class Auth_Controller extends Base_Controller {

	public $restful = true;

    private static $cache_timeout = 10;

	public function get_index() {
		return View::make('home.index');
	}

	public function get_login() {
		$token = Session::token();

        $credentials = array(
			'username' => Input::get('username'),
			'password' => Input::get('password'),
		);

        if(empty($credentials)){
            return Response::json(null, 412);
        }
        else{
            Cache::remember(
                $token,
                function() use($token) { return $token; },
                self::$cache_timeout
            );
            if(Auth::attempt($credentials)){
                return Response::json($token, 200);
            }
            else{
                return Response::json(null, 404);
            }
        }
	}

	public function get_logout() {
		Auth::logout();
		return View::make('home.logout');
	}
}