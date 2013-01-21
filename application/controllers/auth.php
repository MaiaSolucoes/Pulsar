<?php

class Auth_Controller extends Base_Controller {

	public $restful = true;

	public function get_index() {
		return View::make('home.index');
	}

	public function post_login() {
		$credentials = array(
			'username' => Input::get('username'),
			'password' => Input::get('password'),
		);

		return Auth::attempt($credentials)
			? Response::json(Auth::user()->to_array(), 200)
			: Response::json(null, 404);
	}
}