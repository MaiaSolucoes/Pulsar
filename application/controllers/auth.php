<?php

class Auth_Controller extends Base_Controller {

	public $restful = true;

	public function get_index() {
		return View::make('home.index');
	}

	public function get_login() {
		$credentials = array(
			'username' => Input::get('username'),
			'password' => Input::get('password'),
		);
        if(empty($credentials)){
            return Response::json(null, 404);
        }
        else{
            return Auth::attempt($credentials)
                ? Response::json(Auth::user()->gid, 200)
                : Response::json(null, 404);
        }
	}
}