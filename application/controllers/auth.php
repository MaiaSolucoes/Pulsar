<?php

class Auth_Controller extends Base_Controller {

    public $restful = true;

    private static $cache_timeout = 10;

    public function get_index() {
        return View::make('home.index');
    }

    public function get_login() {

        $cache_id = 'auth_'.Input::get('username');

        Cache::put('auth','verdade',10);
        $auth = Cache::get('auth');

        $credentials = array(
            'username' => Input::get('username'),
            'password' => Input::get('password'),
        );

        if(empty($credentials['username']) or empty($credentials['password'])){
            return Response::json('embranco', 412);
        } else {
            $response = Cache::remember(
                $cache_id,
                function() use($credentials,$auth) {
                    if(Auth::attempt($credentials)){
                        return Response::json( $auth, 200);

                    }else{
                        return Response::json('auth nao criado', 404);
                    }
                },
                self::$cache_timeout
            );

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