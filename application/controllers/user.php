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

        $cache_token = Cache::has('token') ? Cache::get('token') : null;//pega o token do cache
        $user = null;


        if($cache_token == null){
            $user = 'Cache expirado';
        } else {
            if(Auth::check()){

                //verificar se existe um cache com os dados do cara, se tiver retorna
                //se nao tiver consulta o banco cria um cache e retorna

                //nao podemos usar esse Auth::user()
                //pq nao sei quem eh esse user? se tiver 10 cache de 10 cara? quem sera esse Auth::user()???
                //nao sei se alguem souber me explica...ate amanha pessoal



                $user = Auth::user()->to_array();

            };
        }

        return $user;

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
