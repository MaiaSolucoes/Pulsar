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
	private static $cache_timeout = 10;//isso ainda nao uso

	public function get_user(){

        $verify = Cache::has(Input::get('token')) ? true : false;

        if ($verify) {

            if (Input::has('email') and Input::has('id')) {

                $status = 500;

            } elseif (Input::has('email')) {

                $status = 200;
                $field = 'email';

            } elseif (Input::has('id')) {

                $status = 200;
                $field = 'id';

            } else {

                $status = 400;

            }
            $fields_bd = array('id', 'gid', 'display_name', 'first_name', 'last_name', 'created_at', 'updated_at');

            if($status == 200){

                if(Cache::has(Input::get($field))){

                    $user = Cache::get(Input::get($field));

                } else {

                    $user = DB::table('users')->where($field, '=', Input::get($field))->get($fields_bd);
                    Cache::put(Input::get($field),$user,1);

                }

            }

            $message = Helper\HTTP::get_code_message($status);

            return Response::json(array('status' => $message, Input::get($field) => $user), $status);
        }

        return Response::json(array('status' => Helper\HTTP::get_code_message(401)), 401);

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
