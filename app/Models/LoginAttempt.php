<?php 

namespace App\Models;

use Core\BaseModelEloquent;


class LoginAttempt extends BaseModelEloquent
{
	
	public $table = 'login_attempts';

	public $timestamps = false;

	protected $fillable = ['user_id'];
}