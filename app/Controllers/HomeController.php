<?php

namespace App\Controllers;

use Core\BaseController;

class HomeController extends BaseController {

	public function index(){
		return $this->view('home', true, [
			'title'	=> 'Home'
		]);
	}

}