<?php

class Auth_Controller extends Base_Controller {

    public $restful = true;

    private static $cache_timeout = 1;

    public function get_index() {
        return View::make('home.index');
    }

    public function get_login() {

        $credentials = array(
            'username' => Input::get('username'),
            'password' => Input::get('password'),
        );
        $time = Auth_Controller::$cache_timeout;

        if(empty($credentials['username']) or empty($credentials['password'])){
            return Response::json(null, 412);
        } else {
            $cache_id = Input::get('username');
            $response = Cache::remember(
                $cache_id,
                function() use($credentials) {

                    if(Auth::attempt($credentials)) {
                        return Session::token();
                    } else {
                        return null;
                    }
                },
                $time
            );
            if($response == null) {
                return $this->get_logout();
            }
            return Response::json(array($cache_id => $response),200);
        }
    }

    public function get_logout(){

        $user = Input::get('username');

        $token = Input::get('token');

        $response = Response::json(false,200);

        $cache_token = false;

        if(empty($user) or empty($token)){

            return $response;

        } else {

            Cache::has($user) ? $cache_token = Cache::get($user):null;

            if($token == $cache_token){

                Cache::forget($user);
                $response = Response::json(true,200);

            }

        }

        return $response;

    }

    public function get_check(){
        
        $user = Input::get('username');

        $token = Input::get('token');

        $response = Response::json(false,200);

        $cache_token = false;

        if(empty($user) or empty($token)){

            return $response;

        } else {

            Cache::has($user) ? $cache_token = Cache::get($user) : null;

            $token == $cache_token ? $response = Response::json(true,200) : null;

            return $response;
        }

    }

}