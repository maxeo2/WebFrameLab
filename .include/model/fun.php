<?php

function riDimeImmagine($percorsoImmaginaDaRidim, $nuovaAltezza, $nuovaLarghezza, $percorsoInCuiSalvareNuovaImg, $qualita = 75, $propor = 0) {
// Ottengo le informazioni sull'immagine originale
    list($width, $height, $type, $attr) = getimagesize($percorsoImmaginaDaRidim);

// Creo la versione 120*90 dell'immagine (thumbnail)
    $thumb = imagecreatetruecolor($nuovaLarghezza, $nuovaAltezza);
    $source = imagecreatefromjpeg($percorsoImmaginaDaRidim);
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $nuovaLarghezza, $nuovaAltezza, $width, $height);

// Salvo l'immagine ridimensionata
    imagejpeg($thumb, $percorsoInCuiSalvareNuovaImg, $qualita);
}

function forceDownload($path, $name) {
    header('Pragma: private');
    header('Cache-control: private, must-revalidate');
    header("Content-Type: application/octet-stream");
    header("Content-Length: " . (string) (filesize($path)));
    header('Content-Disposition: attachment; filename="' . ($name) . '"');
    readfile($path);
    exit;
}

function get_dir_size($dir_name) {
    $dir_size = 0;
    if (is_dir($dir_name)) {
        if ($dh = @opendir($dir_name)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    if (is_file($dir_name . "/" . $file)) {
                        $dir_size += filesize($dir_name . "/" . $file);
                    }
                    /* check for any new directory inside this directory */
                    if (is_dir($dir_name . "/" . $file)) {
                        $dir_size += get_dir_size($dir_name . "/" . $file);
                    }
                }
            }
        }
    }
    @closedir($dh);
    return $dir_size;
}

function cripta_stringa($data, $key) {
    if (strlen($key) < 32)
        $key = md5($key);
    $ld = strlen($data);
    $lk = strlen($key);
    for ($i = 0, $crdata = ""; $i < $ld; $i++) {
        $crdata .= sprintf("%02X", (ord($data[$i])) ^ (ord($key[$i % $lk])));
    }
    return $crdata;
}

function decripta_stringa($cdata, $key) {
    if (strlen($key) < 32)
        $key = md5($key);
    $ld = strlen($cdata);
    $lk = strlen($key);
    for ($i = 0, $data = ""; $i < $ld; $i+=2) {
        $data .= chr((hexdec(substr($cdata, $i, 2))) ^ (ord($key[($i >> 1) % $lk])));
    }
    return $data;
}

function saveFile($what, $where) {  
        $a = fopen($where, "w+");
        fwrite($a, $what);
        fclose($a);
}

function contCartellaSuddiviso($percorso) {
    $a = contenutoCartella($percorso);
    $k = 0;
    $j = 0;
    $listaCartelle = null;
    $listaFiles = null;
    for ($i = 0; $i != count($a); $i++) {
        if (is_dir($percorso . "/" . $a[$i])) {
            $listaCartelle[$k] = $a[$i];
            $k++;
        } else {
            $listaFiles[$j] = $a[$i];
            $j++;
        }
    }
    if (!empty($listaCartelle))
        sort($listaCartelle);
    if (!empty($listaFiles))
        sort($listaFiles);

    $contenuto[0] = $listaCartelle;
    $contenuto[1] = $listaFiles;
    return $contenuto;
}

function contenutoCartella($percorso) {
    $contenuto = null;
    $i = 0;
    if ($handle = @opendir($percorso)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $contenuto[$i] = $file;
                $i++;
            }
        }
        closedir($handle);
    }
    return $contenuto;
}

function ordinaStringa($separatore, $stringaDaOrdinare) {
    $elenco = explode($separatore, $stringaDaOrdinare);
    sort($elenco);
    $stringaOrdinata = implode($separatore, $elenco);
    return $stringaOrdinata;
}

function eliminaCartella($dir) {
    if ($objs = @glob($dir . "/*")) {
        foreach ($objs as $obj) {
            @is_dir($obj) ? eliminaCartella($obj) : @unlink($obj);
        }
    }
    @rmdir($dir);
}

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

function aspettatempo($secondi_da_aspettare = 1) {
    $time_start = microtime_float();
    usleep(100);
    $time = 0;
    while ($time < $secondi_da_aspettare) {
        $time_end = microtime_float();

        $time = $time_end - $time_start;
    }
}

function onlyAlphanum($stringa, $stringCarAggiuntivi = "") {

    $nuovaStr = "";
    for ($i = 0; $i < strlen($stringa); $i++) {
        if (strtoupper($stringa[$i]) == "A" || strtoupper($stringa[$i]) == "B" || strtoupper($stringa[$i]) == "C" || strtoupper($stringa[$i]) == "D" || strtoupper($stringa[$i]) == "E" || strtoupper($stringa[$i]) == "F" || strtoupper($stringa[$i]) == "G" || strtoupper($stringa[$i]) == "H" || strtoupper($stringa[$i]) == "I" || strtoupper($stringa[$i]) == "J" || strtoupper($stringa[$i]) == "K" || strtoupper($stringa[$i]) == "L" || strtoupper($stringa[$i]) == "M" || strtoupper($stringa[$i]) == "N" || strtoupper($stringa[$i]) == "O" || strtoupper($stringa[$i]) == "P" || strtoupper($stringa[$i]) == "Q" || strtoupper($stringa[$i]) == "R" || strtoupper($stringa[$i]) == "S" || strtoupper($stringa[$i]) == "T" || strtoupper($stringa[$i]) == "U" || strtoupper($stringa[$i]) == "V" || strtoupper($stringa[$i]) == "W" || strtoupper($stringa[$i]) == "X" || strtoupper($stringa[$i]) == "Y" || strtoupper($stringa[$i]) == "Z" || strtoupper($stringa[$i]) == "0" || strtoupper($stringa[$i]) == "1" || strtoupper($stringa[$i]) == "2" || strtoupper($stringa[$i]) == "3" || strtoupper($stringa[$i]) == "4" || strtoupper($stringa[$i]) == "5" || strtoupper($stringa[$i]) == "6" || strtoupper($stringa[$i]) == "7" || strtoupper($stringa[$i]) == "8" || strtoupper($stringa[$i]) == "9")
            $nuovaStr.=$stringa[$i];
        else {
            for ($k = 0; $k < strlen($stringCarAggiuntivi); $k++)
                if ($stringCarAggiuntivi[$k] == $stringa[$i])
                    $nuovaStr.=$stringa[$i];
        }
    }
    return $nuovaStr;
}

function randomStrAlphanum($numerocaratteri) {
	// 65=A 90=Z 48=0 57=9 97=a 122=z
    $stringa = "";
    for ($i = 0; $i < $numerocaratteri; $i++){
		$rand=rand(1, 62);
        if ($rand < 11)
			$stringa.=chr((rand(48, 57))); 
        elseif($rand<37)
			$stringa.=chr((rand(65, 90)));
		else
			$stringa.=chr((rand(97, 122)));
            
	}
        return $stringa;
}

function DaiGiornoITA($day) { // date('w')
    $giorni = array(0 => 'Domenica',
        'Luned&igrave;',
        'Marted&igrave;',
        'Mercoled&igrave;',
        'Gioved&igrave;',
        'Venerd&igrave;',
        'Sabato');
    return $giorni[$day];
}

function DaiMeseITA($month = 0) { // date('n')
    if ($month == 0)
        $month = date('n') - 1;
    else
        $month--;
    $mesi = array(0 => 'Gennaio',
        'Febbraio',
        'Marzo',
        'Aprile',
        'Maggio',
        'Giugno',
        'Luglio',
        'Agosto',
        'Settembre',
        'Ottobre',
        'Novembre',
        'Dicembre');
    return $mesi[$month];
}

function confrontadate($data1, $data2, $formato = "d-m-Y") {
    if (date($formato, $data1) == date($formato, $data2))
        return 1;
}

function traduciData($stringaData) {

    $data = explode("-", $stringaData);

    if (!(count($data) == 3))
        $data = explode("/", $stringaData);


    if ((count($data) == 3 && (strtotime($data[2] . "-" . $data[1] . "-" . $data[0]) || strtotime($data[2] . "/" . $data[1] . "/" . $data[0])))) {
        if (strtotime($data[2] . "-" . $data[1] . "-" . $data[0]))
            $data = strtotime($data[2] . "-" . $data[1] . "-" . $data[0]);
        else
            $data = strtotime($data[2] . "/" . $data[1] . "/" . $data[0]);
    }
    //echo date("d-m-Y",$data);
    return $data;
}

function IVAcompresa($val, $iva=22) {
    $val+=($val * $iva/100);
    return $val;
}

function giornoUtile($ore, $data = 0) {
    $giorni = $ore / 24;
    if ($data == 0)
        $data = time();

    for ($i = 0; $i < $giorni; $i++) {
        if (date("w", $data) == 0) {
            $giorni++;
            $data+=86400;
        }
        if (date("w", $data) == 6) {
            $giorni+=2;
            $data+=172800;
        }
        $data+=86400;
    }
    return $giorni;
}

function primoGiornoSettimana($date = 0) {
    if (empty($date))
        $date = time();
    if (date("w", $date))
        $date-=(date("w", $date) - 1) * (60 * 60 * 24);
    else
        $date-=60 * 60 * 24 * 6;
    $date = traduciData(date("d-m-Y", $date));
    return $date;
}

function sandmail($mail_to, $mail_from, $mail_subject, $mail_body) {

    // Specifico le intestazioni per il formato HTML 
    $mail_in_html = "MIME-Version: 1.0\r\n";
    $mail_in_html .= "Content-type: text/html; charset=utf8\r\n";
    $mail_in_html .= "From: $mail_from";

    // Invio la mail
    if (mail($mail_to, $mail_subject, $mail_body, $mail_in_html)) {
        return 1;
    }
}

function file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
    return mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}

function specialCharAmp($string, $toAmp = 1) {
    if ($toAmp) {
        $string = str_replace("&", "&#38;", $string);
        $string = str_replace("%", "&#37;", $string);
        $string = str_replace("'", "&#39;", $string);
        $string = str_replace('"', "&#34;", $string);
        $string = str_replace("@", "&#64;", $string);
        $string = str_replace("\\", "&#92;", $string);
        $string = str_replace("_", "&#95;", $string);
    } else {
        $string = str_replace("&#37;", "%", $string);
        $string = str_replace("&#39;", "'", $string);
        $string = str_replace("&#34;", '"', $string);
        $string = str_replace("&#64;", "@", $string);
        $string = str_replace("&#92;", "\\", $string);
        $string = str_replace("&#95;", "_", $string);
        $string = str_replace("&#38;", "&", $string);
    }
    return $string;
}

function get_string_between($string, $start, $end){
	if(strpos($string,$start)>=0){
    	$string = " ".$string;
    	 $ini = strpos($string,$start);
    	 if ($ini == 0) return "";
    	 $ini += strlen($start);    
    	 $len = strpos($string,$end,$ini) - $ini;
    	 return substr($string,$ini,$len);
	}
	else
		return "";
}

function readPDFdata($data){
	$dataPdf=array();
	if($datafile=get_string_between($data,'<rdf:RDF','</rdf:RDF')){
		$dataPdf['type']='pdf';
		$dataPdf['mode']=@get_string_between($datafile,"<xmpG:mode>","</xmpG:mode>");
		if($dataPdfTh=get_string_between($datafile,"<xmp:Thumbnails>","</xmp:Thumbnails>")){
			$dataPdf['thumb']['ext']=get_string_between($dataPdfTh,"<xmpGImg:format>","</xmpGImg:format>");
			$dataPdf['thumb']['data']=get_string_between($dataPdfTh,"<xmpGImg:image>","</xmpGImg:image>");
			$dataPdf['thumb']['width']=get_string_between($dataPdfTh,"<xmpGImg:width>","</xmpGImg:width>");
			$dataPdf['thumb']['height']=get_string_between($dataPdfTh,"<xmpGImg:height>","</xmpGImg:height>");
		}
	}
	elseif(substr($data,0,4)=='%PDF'){
		$dataPdf['type']='pdf';
	}
	return $dataPdf;
}
function removeFromString($subject,$replace){
	$parameters = func_get_args();
	
	for($i=1;$i<count($parameters);$i++){
		$subject=str_replace($parameters[$i],"",$subject);
	}
	return $subject;
}
function var_dump_ret($mixed = null) {
  ob_start();
  var_dump($mixed);
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}

function percent($num,$perc,$approx=2){
	if($approx<0)
		return ($perc*$num/100);
	else
		return round($perc*$num/100,$approx);
}
function mkdirToPath($path){
	$mkdrectory_sfl=$path;
	$dir_to_make=array();
	while(!file_exists($mkdrectory_sfl)){
		$dir_to_make[]=$mkdrectory_sfl;
		$mkdrectory_sfl=explode("/",$mkdrectory_sfl);
		unset($mkdrectory_sfl[count($mkdrectory_sfl)-1]);
		$mkdrectory_sfl=implode("/",$mkdrectory_sfl);
	}
	for($i=count($dir_to_make);$i>0;$i--){
		@mkdir($dir_to_make[$i-1]);
	}
}

function get_browsername($user_agent=NULL) {
	if(is_null($user_agent))
		$user_agent=$_SERVER['HTTP_USER_AGENT'];
	
	if (strpos($user_agent, 'MSIE') !== FALSE)
		$browser = 'Microsoft Internet Explorer';
	elseif (strpos($user_agent, 'FxiOS') !== FALSE)
		$browser = 'Firefox for iOS';
	elseif (strpos($user_agent, 'Chrome') !== FALSE)
		$browser = 'Google Chrome';
	elseif (strpos($user_agent, 'Firefox') !== FALSE)
		$browser = 'Mozilla Firefox';
	elseif (strpos($user_agent, 'Opera') !== FALSE)
		$browser = 'Opera';
	elseif (strpos($user_agent, 'Safari') !== FALSE)
		$browser = 'Apple Safari';
	elseif(strpos($user_agent, 'Mobile') !== FALSE)
		$browser = 'Unknown Mobile Browser';
	else
		$browser = 'Unknown'; //<-- Browser not found.
		
	return $browser;
}

        
?>
