<?php

class Auth_Controller extends Base_Controller {

    public $restful = true;

    private static $cache_timeout = 10;

    public function get_index() {
        return View::make('home.index');
    }

    public function get_login() {

        $cache_id = 'auth_'.Input::get('username');
        $credentials = array(
            'username' => Input::get('username'),
            'password' => Input::get('password'),
        );

        if(empty($credentials['username']) or empty($credentials['password'])) {
            return Response::json(null, 412);
        } else {
			if(Auth::attempt($credentials)) {
				$response = Cache::remember(
					$cache_id,
					function() { return Session::token(); },
					self::$cache_timeout
				);
				return Response::json($response, 200);
			} else {
				return Response::json(null, 404);
			}

            if($response->status() == 404) {
                Cache::forget($cache_id);
            }
            return $response;

        }
    }

    public function get_logout() {
        Auth::logout();
        return View::make('home.logout');
    }
}