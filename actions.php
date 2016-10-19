<?php

if (!defined('DOCUMENT_ROOT')) {
    define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT'] . "/");
}
if (!defined('DOCUMENT_SETTINGS')) {
    define("DOCUMENT_SETTINGS", DOCUMENT_ROOT . ".settings/");
}
session_start();

//Inclusioni Base
require_once(DOCUMENT_ROOT . ".include/model/fun.php"); //funzioni generali
require_once(DOCUMENT_ROOT . ".include/model/wfun.php"); //funzioni relative al sito
//Classi
require_once(DOCUMENT_ROOT . ".include/model/classes/Connection.php");  //classe per il mantenimento delle varie sessioni
require_once(DOCUMENT_ROOT . ".include/model/classes/Captcha.php");  //classe per il captcha
require_once(DOCUMENT_ROOT . ".include/model/classes/DBConnection.php"); //classe del database
require_once(DOCUMENT_ROOT . ".include/model/classes/FileManage.php"); //classe per la gestione degli upload;
require_once(DOCUMENT_ROOT . ".include/model/classes/Notification.php"); //classe per gestire notifiche e messaggi di errore
require_once(DOCUMENT_ROOT . ".include/model/classes/Pageloader.php"); //classe per gestire le pagine/get tramite url
require_once(DOCUMENT_ROOT . ".include/model/classes/SettingsManage.php"); //classe per gestire i settaggi
require_once(DOCUMENT_ROOT . ".include/model/classes/User.php");   //classe per gli utenti


if (isset($_SESSION['msg_log'])) {
    $msg_log = $_SESSION['msg_log'];
    unset($_SESSION['msg_log']);
}

//Azioni
$error = array();                //errori
$settings = importSettings();    //vengono importati i settaggi

Pageloader::load();

$dbCon = new DBConnection();   //connette al db

if ($dbCon->isConnected()) {
    if (isset($_SESSION['CID'], $_SESSION['CKEY']))
        Connection::start($_SESSION['CID'], $_SESSION['CKEY'], @$_SESSION['CLG']);
    elseif (isset($_COOKIE['CID'], $_COOKIE['CKEY']))
        Connection::start($_COOKIE['CID'], $_COOKIE['CKEY'], @$_COOKIE['CLG']);
    else
        Connection::start();
}
?>