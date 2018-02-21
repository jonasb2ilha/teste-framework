<?php 

namespace Core;

use App\Config;
use PHPMailer\PHPMailer\PHPMailer;

class Mail {

	public function enviaEmail($email, $name, $token)
	{
		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
	    $mail->SMTPDebug = 2;   
	    $mail->CharSet = 'UTF-8';                              
	    $mail->isSMTP();
	    $mail->SMTPSecure = 'tls';                                      
	    $mail->Host = '	smtp.mailtrap.io';  	
	    $mail->SMTPAuth = true;                              
	    $mail->Username = 'cc2403aca2a13b';                 
	    $mail->Password = '919e720d64e23c';                          
	    $mail->Port = 465;  
	    $mail->setFrom('Blog@hotmail.com', 'Blog csgo');
	    $mail->addAddress($email, $name);     // Add a recipient
	   	$mail->isHTML(true);                                  // Set email format to HTML
	    $mail->Subject = 'Email de recuperação de senha';
	    $mail->Body    = 'Acesse o link <b><a href="'. Config::baseUrl() .'/recovers/account/token/'. $token .'" target="_blank">Trocar senha</a></b> para a redefinição de sua senha!';

	    if ($mail->send()) {
	    	return true;
	    } else {
	    	return false;
	    }

		
	}

}