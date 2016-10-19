<?php

class User {

	private static $idUser = NULL;
	private static $userData = NULL;
	private static $name = NULL;
	
    //Controlla se il login è corretto
    public static function correctLogin($mail, $password) {
        global $dbCon;
		if (filter_var($mail, FILTER_VALIDATE_EMAIL)){
	        $query = "SELECT * FROM `users` WHERE `mail` = '" . strtolower($mail) . "' AND `password` = '" . hash("sha256",$password) . "' ";
			 $res=$dbCon->arrayRes($query);
			 return $res['ID'];
		}
		else{
			Notification::addWarning("USE-F0001");
			return;
		}
    }

    //Crea all'intanto del database un nuovo utente settando i parametri fondamentali
    public static function registerNewUser($mail, $name, $password, $active = 0) {
        global $dbCon;
		
		$mail=strtolower($mail);
		
		if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
			
			$password=hash("sha256",$password);
			$active*=1;
			$activation_key=randomStrAlphanum(64);
			
			if(!self::mailExists($mail)){
				$name=htmlspecialchars(ucwords(strtolower($name)), ENT_QUOTES);
				$clientIP=htmlspecialchars($_SERVER["REMOTE_ADDR"], ENT_QUOTES);
				$query="INSERT INTO `".$dbCon->get("hostDB").
						"`.`users` (`ID`, `mail`, `name`, `password`,`time_reg`, `power_user`,`activation_key`, `clientIP` ) VALUES (NULL, '".$mail.
						"', '".$name."', '".$password."', NOW(), ".$active.", '".$activation_key."', '".$clientIP."');";
				$userID=$dbCon->exeQueryToGetID($query);
				if($active==0){
					//mail per attivazione account
					//mail()
				}
				return $userID;
			}
			else{
				Notification::addWarning("USE-F0002");
			}
		}
		else{
			Notification::addWarning("USE-F0003");
		}
	}

    //Ritorna l'id dell'utente in caso di successo
    public static function mailExists($mail, $id = 0) {
		global $dbCon;
		if (filter_var($mail, FILTER_VALIDATE_EMAIL) && $id==0) {
			$query="SELECT * FROM `users` WHERE `mail` = '".$mail."' ";
		}
		else{
			$mail*=1;
			$query="SELECT * FROM `users` WHERE `ID` = ".$mail;
		}
		
		return $dbCon->exeQuery($query);
	}

    public static function activeUser($mail, $key, $isID = 0) {
        global $dbCon;
		
		if($isID==0){
			if(self::mailExists($mail)){
				$queryUpdate="UPDATE `".$dbCon->get("hostDB")."`.`users` SET `power_user` = '1', `activation_key` = '' WHERE `users`.`mail` = '".$mail."';";
				$condition="WHERE `users`.`mail` = ";
			}
			else{
				if(filter_var($mail, FILTER_VALIDATE_EMAIL))
					Notification::addWarning("USE-F0004");
				else
					Notification::addWarning("USE-F0005");
				return;
			}
		}
		else{
			$mail*=1;
			$queryUpdate="UPDATE `".$dbCon->get("hostDB")."`.`users` SET `power_user` = '1', `activation_key` = '' WHERE `users`.`ID` = ".$mail.";";
			$condition="WHERE `users`.`ID` = ";
		}
		$queryCorrectUpdate="SELECT * FROM `users` ".$condition."'".$mail."' AND `activation_key` = '".onlyAlphanum($key)."' ";
		if($dbCon->exeQuery($queryCorrectUpdate))
			$dbCon->exeQuery($queryUpdate);
		else
			Notification::addWarning("USE-F0006");
    }
	public static function loadUser($ID){
		global $dbCon;
		$query="SELECT * FROM `users` WHERE `ID` = ".$ID;
		$data=$dbCon->multiArrayRes($query);
		if($data){
			self::$userData=$data[0];
			self::$idUser=$ID;
			self::$name=$data[0]['name'];
		}
		else{
			Notification::addWarning("USE-F0007");
		}
		
		
	}
	
	public static function mailFromUser($id){
		global $dbCon;
		$query="SELECT `mail` FROM `users` WHERE `ID` = ".$id*1;
		$res=$dbCon->selection($query);
		if($res)
			return $res['mail'];
		else
			return false;
		
	}
	
	public static function getList($from=0, $n_res=10){
		global $dbCon;
		$query="SELECT * FROM `users` ORDER BY `ID` DESC LIMIT ".($from*1)." ,".$n_res;
		return $dbCon->selectionAll($query);
	}
	
    public static function getIdUser() {
        return self::$idUser;
    }
	public static function getName() {
        return self::$name;
    }

    public static function getDataUser($data) {
        return self::$userData[$data];
    }

}

?>