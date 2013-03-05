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

	public function get_user() {
		$user = User::find(Input::get('id'));
		return $user instanceof User
			? Response::json($user->to_array(), 200)
			: Response::json(User::all(), 404);
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
		return Response::json(array($message, $issues), $status);
	}
    public function testePrepare(){

        $const = new Constraint();
        //criar valores de teste para mandar para o metodo abaixo


        //$const->get_issues();




    }

}
