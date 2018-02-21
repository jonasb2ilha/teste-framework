<?php

namespace App\Controllers;

use App\Controllers\Validate\Validate;
use App\Models\User;
use Core\BaseController;
use Core\Password;
use Core\Redirect;

class UserController extends BaseController {

	public function index(){
		return $this->view('registre', true, [
			'title'	=> 'My frame'
		]);
	}

	public function store() {

		$data = $this->post();
		$getErrors = Validate::registreUser($data);
		
		if ($getErrors) 
			return Redirect::route('/registre', [
				'errors'	=> $getErrors
			]);

		$dados = [
			'name'		=> $data->name,
            'user'      => $data->user,
	   		'email'		=> $data->email,
	   		'password'	=> Password::hash($data->password),
	   		'role'		=> 1,
	   		'status'	=> 1
		];

		if (User::create($dados)) {

			Auth($data, true);

		}
				
	}

}