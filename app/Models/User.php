<?php

namespace App\Models;

use Core\BaseModelEloquent;

class User extends BaseModelEloquent
{
	public $table = 'users';

	public $timestamps = false;

	protected $fillable = ['name', 'user', 'email', 'password', 'role', 'status'];

	
}