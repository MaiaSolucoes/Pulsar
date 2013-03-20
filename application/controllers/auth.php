<?php

class Auth_Controller extends Base_Controller {

    public $restful = true;

    private static $cache_timeout = 2;

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

            if(Auth::attempt($credentials)) {

                $token = Session::token();
                Cache::put($token,$credentials['username'],$time);
                return Response::json(array('token' => $token,'username' => $credentials['username']),200);

            } else {

                return $this->get_logout();

            }

        }

    }

    public function get_logout(){

        $token = Input::get('token');

        if(!is_null($token)){

            $cache_token = Cache::has($token) ? true : false;

            if($cache_token){

                Cache::forget($token);
                return Response::json(true,200);

            }

            return Response::json(false,200);

        }

    }

    public function get_check(){

        $token = Input::get('token');

        if(!is_null($token)){

            $user = Cache::has($token) ? Cache::get($token) : null;

            if(!is_null($user)){

                Cache::put($token, $user,1);
                return Response::json(true,200);

            }

        }

        return Response::json(false,200);

    }

}