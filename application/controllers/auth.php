<?php

class Auth_Controller extends Base_Controller {

	public $restful = true;
	public $return_type = 'json';
	private $allowed_return_types = array(
		'json',
		'xml',
		'plaintext'
	);

	public function get_index() {
		return View::make('home.index');
	}

	private function set_return_type($type) {
		$default = current(reset($this->allowed_return_types));

		if(in_array($type, $this->allowed_return_types)) {
			$this->return_type = $type;
		} else {
			Log::auth_set_return_type('Tipo de retorno não permitido: ['.$type.']. Definindo para padrão: ['.$default.']');
			$this->return_type = $default;
		}

		return true;
	}



}