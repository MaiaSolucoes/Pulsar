<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ricardo
 * Date: 17/01/13
 * Time: 02:56
 * To change this template use File | Settings | File Templates.
 */
class User_Controller extends Base_Controller {

	public $restful = true;
	private static $cache_timeout = 10;

	public function get_user(){

        $username = Input::get('username');
        $token = Input::get('token');
        if(is_null($username) or is_null($token)){

            return Response::json('blank');

        }
        $cache_token = Cache::has($username) ? Cache::get($username) : null;
        $validation = $token == $cache_token ? true : false;

        $data = null;

        if($validation){

            $data = DB::query('SELECT * FROM users WHERE email = ?', array(Input::get('username')));

        } else {

            $data = 'Cache expired';

        }

        return Response::json($data);

	}

	public function post_user() {
		if(Auth::check() or true) {
			try{
				$user = Input::get('id') > 0 ? User::find(Input::get('id')) : new User();
				$issues = $user->prepare(Input::get());

				$status = empty($issues) ? $user->save() : false;
			} catch(Exception $e) {
				Log::post_user($e->getMessage());
				$status = false;
			}

			$status = $status ? 200 : 500;

			$message = Helper\HTTP::get_code_message($status);

		}
		return Response::json(
			empty($issues)
				? $message
				: $issues,
			$status
		);
	}

}
