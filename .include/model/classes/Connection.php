<?php

class Connection {

    private static $idConnection = NULL;
    private static $keyConnection = NULL;
    private static $isUser = NULL;

    public static function start($id = -1, $key = -1, $idUser = -1) {
        if ($idUser > 0 && $id > 0) {   //Utente loggato
            if (self::connectionExists($id, $key, $idUser)) {
                $idConnection = self::connectionExists($id, $key, 1);
                self::$idConnection = $idConnection[0];
                self::$keyConnection = $key;
                User::loadUser($id);
            } else {                    //cooke vecchio
                self::makeNewConnection();
            }
        } else {
            if (empty($key)) {           //nessun cookies
                self::makeNewConnection();
            } elseif (self::connectionExists($id, $key, $idUser) && $id > 0) {
                self::$idConnection = $id;
                self::$keyConnection = $key;
            } else {
                self::makeNewConnection();
            }
        }
    }

    //verifica l'esistenza di una connessione
    public static function connectionExists($id, $key, $idUser) {
        global $dbCon;
        if ($idUser > 0)
            $query = "SELECT * FROM `connections` WHERE `IDuser` = " . ($id * 1) . " AND `keyConnection` = '" . onlyAlphanum($key) . "' ";
        else
            $query = "SELECT * FROM `connections` WHERE `ID` = " . ($id * 1) . " AND `keyConnection` = '" . onlyAlphanum($key) . "' ";
        return $dbCon->arrayFromQuery($query);
    }

    //elimina la connesione corrente
    public static function removeCurrentConnection() {
        global $dbCon;
        $query = "DELETE FROM `" . $dbCon->get("hostDB") . "`.`connections` WHERE `connections`.`ID` = " . self::getIdConnection();
        $a;
        return $dbCon->exec($query);
    }

    //Genera una nuova connessione
    public static function makeNewConnection($idUser = 0, $request = false) {
        global $dbCon, $_SESSION;
        $idUser*=1;


        $keyConnection = randomStrAlphanum(64);
        if (empty(self::connectionKeyExist($keyConnection))) {
            $clientIP = $_SERVER["REMOTE_ADDR"];

            $firstConnection = htmlspecialchars($_SERVER["HTTP_HOST"], ENT_QUOTES);
            $lang = @htmlspecialchars($_SERVER['HTTP_ACCEPT_LANGUAGE'][0] . $_SERVER['HTTP_ACCEPT_LANGUAGE'][1], ENT_QUOTES);
            $browserInfo = @htmlspecialchars($_SERVER["HTTP_USER_AGENT"], ENT_QUOTES);

            $query = "INSERT INTO `" . $dbCon->get("hostDB") . "`.`connections` (`ID`, `IDuser`, `keyConnection`, `clientIP`,`first_connection`,`lang`, `browser_info`,`time_connection`) VALUES (NULL, "
                    . $idUser . ", '" . $keyConnection . "', '" . $clientIP . "', '" . $firstConnection . "', '" . $lang . "','" . $browserInfo . "', NOW());";

            $idConn = $dbCon->exeQueryToGetID($query);
            self::$idConnection = $idConn;
            self::$keyConnection = $keyConnection;

            if ($request) {
                $_SESSION['CKEY'] = $keyConnection;
                $_SESSION['CLG'] = 1;
                $_SESSION['CID'] = $idUser;
            } else {

                if ($idUser) {
                    //if($idUser>0)

                    setcookie("CLG", 1, time() + 30758400, "/");
                    setcookie("CID", $idUser, time() + 30758400, "/");
                } else {
                    setcookie("CID", $idConn, time() + 30758400, "/");
                    setcookie("CLG", 0, time() + 30758400, "/");
                }
                setcookie("CKEY", $keyConnection, time() + 30758400, "/");
            }
            self::tooManyConnections($clientIP);
        } else
            self::makeNewConnection();

        if (Captcha::is_req()) {
            Captcha::update();
        }
    }

    //Verifica l'esistenza di una connessione ritornando l'ID della connesssione
    private static function connectionKeyExist($key) {
        global $dbCon;

        $query = "SELECT * FROM `connections` WHERE `keyConnection` = '" . $key . "' ";
        $res = $dbCon->arrayFromQuery($query);
        if (!empty($res))
            return $res[0];
    }

    //Se le connessioni da un unico ip sono sopra quelle consentite elimina le connessioni più vecchie
    private static function tooManyConnections($ip) {
        global $dbCon;
        global $settings;

        $query = "SELECT * FROM `connections` WHERE `clientIP` = '" . $ip . "' ORDER BY `time_connection` ASC";
        $res = $dbCon->multiArrayRes($query);

        if (count($res) > $settings['maxConnectionsByIP']) {
            Notification::addWarning("CON-F0002");
            for ($i = 0; $i < count($res) - $settings['maxConnectionsByIP']; $i++) {
                $query = "DELETE FROM " . $dbCon->get("hostDB") . ".`connections` WHERE `connections`.`ID` = " . $res[$i]['ID'] . ";";
                $dbCon->exeQuery($query);
            }
        }
    }

    public static function getList($from = 0, $n_res = 10) {
        global $dbCon;
        $query = "SELECT * FROM `connections` ORDER BY `ID` DESC LIMIT " . ($from * 1) . " ," . $n_res;
        return $dbCon->selectionAll($query);
    }

    //Metodi GETTER
    public static function getIdConnection() {
        return self::$idConnection;
    }

    public static function getKeyConnection() {
        return self::$keyConnection;
    }

}

?>