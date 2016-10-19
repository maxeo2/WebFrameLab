<?php
//
if(!empty($_POST['mail']) && !empty($_POST['name']) && !empty($_POST['password'])){
	
	$mail=$_POST['mail'];
	$name=$_POST['name'];
	$password=$_POST['password'];
	
	$coorectCaptcha=true;
	if(Captcha::is_req() || !empty($_POST['captcha'])){
		$coorectCaptcha=Captcha::check(@$_POST['captcha']);
	}
	
	if($coorectCaptcha){
		if(User::mailExists($mail)){
		header("Location: ".Pageloader::b_pg()."/errore/Mail già esistente");
		}
		else{
                        $idConn=Connection::getIdConnection();
			$user=User::registerNewUser($mail, $name, $password);
			Connection::removeCurrentConnection();
			Connection::makeNewConnection($user);
                        Cart::merge($user,$idConn, $force=0);
                        
			header("Location: /");
		}
	}
	else
		header("Location: ".Pageloader::b_pg()."/errore/Captcha non valido.");
	
}
else
header("Location: ".Pageloader::b_pg()."/errore/Dati mancanti.");
?>