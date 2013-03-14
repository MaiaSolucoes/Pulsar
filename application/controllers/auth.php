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
        $token = Session::token();

        if(empty($credentials['username']) or empty($credentials['password'])){
            return Response::json(null, 412);
        } else {
            $response = Cache::remember(
                'token',
                function() use($credentials,$time,$token) {
                    if(Auth::attempt($credentials)) {
                        return $token;
                    } else {
                        return null;
                    }
                },
                $time
            );
            if($response == null) {
                return $this->get_logout();
            }
            return Response::json(array('token' => $response),200);
        }
    }

    public function get_logout() {
        if (Auth::check()) {
            Cache::forget('token');
            Auth::logout();
            return "VOCE DESLOGOU E APAGOU O CACHE";
        } else {
            return "VOCE NAO ESTA AUTENTICADO PARA FAZER O LOGOUT";
        }
    }


    public function get_check(){
        return Cache::has('token') ? Response::json(true,200) : Response::json(false,200);

        //return Response::json($token,200)
    }

}