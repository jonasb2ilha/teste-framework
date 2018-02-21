<?php 

use App\Config;


/*ROUTES DO SITE*/
$route[] = ['/', 			'HomeController@index'];


/* ROUTES LOGIN */

$route[] = ['/login', 						'LoginController@index'];
$route[] = ['/loginAuth', 					'LoginController@login'];
$route[] = ['/logout', 						'LoginController@logout'];
$route[] = ['/recuperar', 					'LoginController@recoversPage'];
$route[] = ['/recovers/account', 			'LoginController@recoversAccount'];
$route[] = ['/recovers/account/token/{id}', 'LoginController@recoversNewPasswordPage'];
$route[] = ['/recovers/account/exchange', 	'LoginController@recoversNewPassword'];


/* CADASTRAR USER */
$route[] = ['/registre', 			'UserController@index'];
$route[] = ['/registre/store', 		'UserController@store'];


$route[] = ['/admin/home', 'Admin\AdminHomeController@index', ['middleware' => Config::auth() , 'role'	=> Config::roleAdmin()]];





$route[] = ['/teste', 'TesteController@index'];











return $route;