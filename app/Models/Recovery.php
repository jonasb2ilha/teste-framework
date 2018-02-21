<?php

namespace App\Models;

use Core\BaseModelEloquent;

class Recovery extends BaseModelEloquent {

	public $table = 'recoverys';

	public $timestamps = false;

	protected $fillable = ['user_id', 'token'];


}