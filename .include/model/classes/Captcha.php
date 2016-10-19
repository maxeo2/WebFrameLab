<?php

class Captcha {
	public static function update(){
		global $dbCon;
		
		$key[0]="".rand(0,9).rand(0,9).rand(0,9);
		$key[1]="".rand(0,9).rand(0,9).rand(0,9);
		
		$query="UPDATE `".$dbCon->get("hostDB")."`.`connections` SET `captcha_key` = '".$key[0].$key[1]."' WHERE `connections`.`ID` = ".Connection::getIdConnection().";";
		
		if($dbCon->exe($query)){
			return $key;
			
		}else{
			Notification::addWarning("CAP-F0001");
			return false;
		}
		
	}
	public static function generate(){
		$key=self::update();
		if($key){
			self::make($key[0],$key[1]);
		}
	}
	public static function make($testo1,$testo2){
		global $settings;
		
		header("Content-type: image/png");
		$backround=$settings['kaptcha']['path_bg'];
		$font=$settings['kaptcha']['path_font'];
		$img = imagecreatefrompng(DOCUMENT_ROOT.$backround[rand(0,4)]);
		
		$r=rand(1,3);
		if($r==1){
			$c1=rand(0,50);
			$c2=rand(0,50);
			$c3=rand(0,50);
		}
		elseif($r==2){
			$c1=rand(0,50);
			$c2=rand(0,50);
			$c3=rand(200,250);
		}
		elseif($r==3){
			$c1=rand(130,150);
			$c3=rand(70,120);
			$c2=rand(0,50);
		}
		$color = imagecolorallocate($img,$c1,$c2,$c3); 
		
		imagealphablending($img, true);
		imagesavealpha($img, true);
		imagettftext($img,rand(90,130),rand(-10,10),10,110,$color,DOCUMENT_ROOT.$font[0],$testo1);
		imagettftext($img,rand(90,130),rand(-10,10),160,110,$color,DOCUMENT_ROOT.$font[1],$testo2);
		imagepng($img); // Mostra l'immagine creata con tutte le funzioni
	}
	public static function check($captcha){
		global $error;
		global $dbCon;
                $a=array(':captcha' => $captcha);
		$query="SELECT * FROM `connections` WHERE `ID` = ".Connection::getIdConnection()." AND `captcha_key` = :captcha";
		$res=$dbCon->prepare($query);
		$res->execute(array(':captcha' => $captcha));
		$res=$res->fetch();
		self::update();
		if(!empty($res))		
			return true;
		else{
			return false;
		}
	}
	public static function is_req(){
		global $dbCon;
		global $settings;
		$n_elements=$dbCon->countElements("users",$_SERVER['REMOTE_ADDR'],"clientIP");
		if($n_elements>$settings['maxRegBeforeCaptcha'])
			 return true;
		else
			return false;
	}
	
}
?>