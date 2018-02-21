<?php 

namespace Core;

class Password
{
	public static function hash($pass)
	{
    	return password_hash($pass, PASSWORD_BCRYPT);
	}


	public static function verify($password, $hash) 
	{
    	return password_verify($password, $hash);
	}
}