<?php 

namespace App;

class Config 
{

	const role_admin = 'admin';
	const role_user  = 'user';
	const auth  	 = 'auth';
	const base_url   = 'http://localhost:8000';

	public static function roleAdmin()
	{
		return self::role_admin;
	}

	public static function roleUser()
	{
		return self::role_user;
	}

	public static function auth()
	{
		return self::auth;
	}

	public static function baseUrl() {
		return self::base_url;
	}

}