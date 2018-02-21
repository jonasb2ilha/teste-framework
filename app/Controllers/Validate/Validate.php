<?php 

namespace App\Controllers\Validate;

use App\Models\User;
use Core\Valida;


class Validate 
{

	private static $valid;

	public static function login($data) 
	{
		self::$valid = new Valida;
		self::$valid->set('user', $data->user)->is_required()->is_string();
        self::$valid->set('password', $data->password)->is_required();

        $erros = self::$valid->get_errors();

        if (!self::$valid->validate()) {
        	return $erros;
        }
	}

	public static function registreUser($data) 
	{
		self::$valid = new Valida(new User);
		self::$valid->set('name', $data->name)->is_required()->is_string();
		self::$valid->set('user', 	$data->user)->is_required()->is_string()->is_unique('user');;
        self::$valid->set('email', 	$data->email)->is_required()->is_email()->is_unique('email');
        self::$valid->set('password', $data->password)->is_required()->min_length(4);
        self::$valid->set("conf", 	$data->password, $data->conf)->is_required()->min_length(4)->is_match('pwd');

		$erros = self::$valid->get_errors();

		if (!self::$valid->validate()) {
			return $erros;
		}
	}

	public static function email($data) {
		self::$valid = new Valida(new User);
		self::$valid->set('email', 	$data->email)->is_required()->is_email();

		$erros = self::$valid->get_errors();

		if (!self::$valid->validate()) {
			return $erros;
		}
	}

	public static function newPassword($data) {
		self::$valid = new Valida();
		self::$valid->set('password', $data->password)->is_required()->min_length(4);
        self::$valid->set("conf", 	$data->password, $data->conf)->is_required()->min_length(4)->is_match('conf');
		self::$valid->set('token', $data->token)->is_required();

		$erros = self::$valid->get_errors();

		if (!self::$valid->validate()) {
			return $erros;
		}
	}
}