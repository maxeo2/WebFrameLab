<?php
if (!defined('DOCUMENT_ROOT')) {
    define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT'] . "/");
}
if (!defined('DOCUMENT_SETTINGS')) {
    define("DOCUMENT_SETTINGS", DOCUMENT_ROOT.".settings/" );
}

function importSettings(){
	if($_SERVER['SERVER_ADDR']==$_SERVER['REMOTE_ADDR'])
		$settingsData=file_get_contents(DOCUMENT_SETTINGS . "settings_local.json");
	else
		$settingsData=file_get_contents(DOCUMENT_SETTINGS . "settings_remote.json");
	while(strlen(get_string_between($settingsData, "/*", "*/"))>0)
		$settingsData= str_replace("/*".get_string_between($settingsData, "/*", "*/")."*/","",$settingsData);
	$jsonFile=json_decode($settingsData,1);
        //$asd2=  json_encode($jsonFile);
        //saveFile($asd2, DOCUMENT_SETTINGS . "settings_local.json");
        
	if(empty($jsonFile)){
		exit("IMPOSSIBILE CARICARE IL FILE DELLE IMPOSTAZIONI");
	}
	else
	return json_decode($settingsData,1);
	
}



?>