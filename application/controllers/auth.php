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
        $time = Auth_Controller::$cache_timeout;
        $token = Session::token();

        if(empty($credentials['username']) or empty($credentials['password'])){
            return Response::json(null, 412);
        } else {
            $response = Cache::remember(
                $cache_id,
                function() use($credentials,$time,$token) {

                    if(Auth::attempt($credentials)) {
                        Cache::put($token, json_encode(Auth::user()->to_array()),$time);
                        return Response::json($token, 200);
                    } else {
                        return Response::json(null, 404);
                    }

                },
                self::$cache_timeout
            );

            if($response->status() == 404) {

                return $this->get_logout();

            }

            return $response;
        }
    }

    public function get_logout() {
        $user = Input::get('username');
        Auth::logout();
        Cache::forget('auth_'.$user);

        return Response::json(null,204);
    }

}