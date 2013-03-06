<?php

class Auth_Controller extends Base_Controller {

	public $restful = true;

    private static $cache_timeout = 10;


	public function get_index() {
		return View::make('home.index');
	}

	public function get_login() {
		$credentials = array(
			'username' => Input::get('username'),
			'password' => Input::get('password'),
		);

        if(empty($credentials)){
            return Response::json('branco', 412);
        }
        else{
            Cache::remember(
                $token = Session::token(),
                function() use($token) { return $token; },
                self::$cache_timeout
            );
            if(Auth::attempt($credentials)){
                return Response::json(Session::token(), 200);
            }
            else{
                return Response::json('nulo', 404);
            }
        }
	}
}