<?php

class Auth_Controller extends Base_Controller {

    public $restful = true;

    private static $cache_timeout = 1;

    public function get_index() {
        return View::make('home.index');
    }

    public function get_login() {

        $cache_id = 'auth_'.Input::get('username');

        $credentials = array(
            'username' => Input::get('username'),
            'password' => Input::get('password'),
        );

        if(empty($credentials['username']) or empty($credentials['password'])){
            return Response::json(null, 412);
        } else {
            $response = Cache::remember(
                $cache_id,
                function() use($credentials) {
                    if(Auth::attempt($credentials)) {
                        $token = Session::token();
                        Cache::put($token, json_encode(Auth::user()->to_array()), 1);
                        return Response::json($token, 200);
                    } else {
                        return Response::json(null, 404);
                    }
                },
                self::$cache_timeout
            );

            /**
             * If Auth::attempt() fails, what happens with its Session::token()?
             * This code fix it in case of 404 response.
             **/
            if($response->status() == 404) {
                Cache::forget($cache_id);
            }
            return $response;

        }
    }

    public function get_logout() {
        Auth::logout();
        $cache_id = 'auth_'.Input::get('username');
        Cache::forget($cache_id);
        Cache::forget();

    }
}
