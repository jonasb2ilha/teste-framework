<?php 

namespace App\Controllers;

use App\Controllers\Validate\Validate;
use App\Models\Recovery;
use App\Models\User;
use Core\BaseController;
use Core\Mail;
use Core\Redirect;
use Core\Request;
use Core\Session;

class LoginController extends BaseController
{

	public function index()
	{
        return $this->view('login', true, [
        	'title'		=> 'Login', 
        ]);
	}

	public function login() {
		
		$data = $this->post();
		$getErrors = Validate::login($data);

		if ($getErrors) {

			return Redirect::route('/login', [
                'errors' => $getErrors
            ]);
		}
		
		Auth($data, null);
		
	}

	public function recoversPage() {
		
		return $this->view('recuperar', true, [
			'title'		=> 'Recuperar conta'
		]);
	}

	public function recoversAccount() {
		$data = $this->post();
		$getErrors = Validate::email($data);

		if ($getErrors) {

			return Redirect::route('/recuperar', [
                'errors' => $getErrors
            ]);
		}

		$dataUser = User::where('email', $data->email)->first();

		if ($dataUser) {
			$email = new Mail;

			$token = token();

			if ($email->enviaEmail($dataUser->email, $dataUser->name, $token)) {

				if (Recovery::where('user_id', $dataUser->id)->first()) {
					Recovery::where('user_id', '=', $dataUser->id)->delete();
				}

				Recovery::create(['user_id' => $dataUser->id, 'token' => $token]);			    

				flash('message', 'Um email com um link de redefinição de senha foi enviada para voce!', 'warning');
				return redirect('/recuperar');

			} else {

				flash('message', 'Error inesperado tente novamente ou contate um administrador!');
				return redirect('/recuperar');

			}

			
		} else {

			flash('message', 'Este <b>E-mail</b> não está cadastrado em nosso sistema!', 'warning');
			return redirect('/recuperar');

		}

		

	}

	public function recoversNewPasswordPage($token) {

		$token = Recovery::where('token', $token)->first();
		if (!$token) {
			flash('message', 'Oops! Código inválido ou expirado!<br> Por favor, solicite um novo link de redefinição.');
			return redirect('/recuperar');
		}

		return $this->view('newPasswordAcc', true, [
			'title'		=> 'Definir nova senha',
			'token'		=> $token->token
		]);
	}

	public function recoversNewPassword() {
		
		$data = $this->post();

		$userIdToken = Recovery::where('token', $data->token)->first();

		if (!$userIdToken) {
			flash('message', 'Oops! Código inválido ou expirado!<br> Por favor, solicite um novo link de redefinição.');
			return redirect('/recuperar');
		}

		$getErrors = Validate::newPassword($data);

		if ($getErrors) {
			return Redirect::route('/recovers/account/token/'. $data->token, [
				'errors' => $getErrors
			]);
		}

		$password = HashPassword($data->password);
		if (User::find($userIdToken->user_id)->update(['password' => $password])) {
			Recovery::find($userIdToken->id)->delete();
			flash('message', 'Senha atualizada com sucesso!', 'success');
			return redirect('/login');
		}

		flash('message', 'Error inesperado, tente novamente ou contate um administrador', 'warning');
		return back();
		 
	}

	public function logout(){
		unset($_SESSION['logged'], $_SESSION['user']);
		flash('message', 'Usuário deslogado com sucesso!', 'dark');
        return redirect('/login');;
	}

}