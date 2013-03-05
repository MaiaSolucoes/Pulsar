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

	public function get_user() {

		$user = null;
		$id = Input::get('id');
		if(is_numeric($id)) {
			$cache_id = 'user_'.$id;
			$user = Cache::remember(
				$cache_id,
				function() use($id) { return User::find($id); },
				self::$cache_timeout
			);
		}

		return $user instanceof User
			? Response::json($user->to_array(), 200)
			: Response::json(null, 404);

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
    public function testePrepare(){
        //vou testar o prepare aqui

    }

}
