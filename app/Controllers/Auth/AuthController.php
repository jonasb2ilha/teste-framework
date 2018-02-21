<?php 

namespace App\Controllers\Auth;

use App\Models\LoginAttempt;
use App\Models\User;
use Core\Redirect;
use Core\Session;

class AuthController {

	private $user;


	public function login($data) {
		
		/*	CARREGA A MODEL USER */

		/*	RECUPERA DADOS DO USUÁRIO */
		$this->user = User::where('user', $data->user)->first();


		/*	VERIFICA SE O USUÁRIO EXISTE */
		if ($this->user) {
			
			/*	VERIFICA SE O USUÁRIO ESTÁ ATIVO */
			if ($this->user->status == 1) {

				/*	VERIFICA SE A SENHA ESTÁ CORRETA*/
				if (Verify($data->password, $this->user->password)) {

					/*	DELETA TENTATIVAS ERRADAS */
					LoginAttempt::where('user_id', '=', $this->user->id)->delete();
					
					/*	LOGIN DE USUÁRIO COMUM */
					if ($this->user->role == 1)
						return $this->loggedInUser();

					/*	LOGIN DE USUÁRIO ADMINISTRADOR */
					if ($this->user->role == 3) 
						return $this->loggedInAdmin();

				} else {

					/*	REGISTRA UMA TENTATIVA DE LOGIN ERRADA */
					LoginAttempt::create(['user_id' => $this->user->id]);

					/*	CONTA QUANTAS TENTATIVAS AINDA RESTAM */
					$attempts = LoginAttempt::where(['user_id' => $this->user->id])->get()->count();

					/*	VERIFICA QUANTAS TENTATIVAS RESTAM */
					if ($attempts == 3 or $attempts == 4) {
						$total = 5 - $attempts;
						return $this->attempts($total);
					}

					/*	SE CHEGAR A 5 TENTATIVAS ERRADAS BLOQUEA O USUÁRIO */
					if ($attempts == 5) {
						User::where('id', $this->user->id)->update(['status' => 0]);
						return $this->isBlock();
					}

					/*	SENHA ERRADA */
					return $this->isNotLoggedIn();
				}

			} else {
				/*	USUPARIO BLOQUEADO */
				return $this->isNotActive();
			}

		} else {
			/*	USUÁRIO NÃO EXISTE */
			return $this->isNotLoggedIn();
		}
		
	}


	private function loggedInUser() {
		Session::sessionSet('logged', true);
		Session::sessionSet('user', $this->user);
		session_regenerate_id();
		return 'user';
	}

	private function loggedInAdmin() {
		Session::sessionSet('logged', true);
		Session::sessionSet('user', $this->user);
		session_regenerate_id();
		return 'admin';
	}

	private function isBlock() {
		flash('message', 'Error. Está conta foi bloqueada por excesso de tentativas.');
		return Redirect::route('/login');
	}

	private function attempts($total) {
		flash('message', 'Suas tentativas estão terminando. Você só tem mais '. $total . ' tentativas!', 'warning');
		return Redirect::route('/login');
	}

	private function isNotActive () {
		flash('message', 'Este usuário encontrase bloqueado em nosso sistema!');
		return Redirect::route('/login');
	}

	private function isNotLoggedIn() {
		flash('message', 'Error, verifique se os dados estão corretos!');
		return Redirect::route('/login');
	}

}