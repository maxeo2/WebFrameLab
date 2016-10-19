<?php

class FileManage {

    private static $location;
    private static $files_list;

    public static function loadSettings() {
        global $settings;
        self::$location = DOCUMENT_ROOT . $settings['saved_files_location'] . "/";
        mkdirToPath(self::$location);
    }

    public static function loadFiles($cart = NULL) {
        self::loadSettings();
        global $dbCon;

        if (User::getIdUser())
            $query = "SELECT * FROM `files_list` WHERE  `IDuser` = " . User::getIdUser();
        else
            $query = "SELECT * FROM `files_list` WHERE `IDconnection` = " . Connection::getIdConnection();

        if ($cart != '*')
            if (!is_null($cart))
                $query.=" AND `IDcart` =" . ($cart * 1);
            else
                $query.=" AND `IDcart` IS NULL";

        $res = $dbCon->multiArrayRes($query);
        self::$files_list = $res;
        return $res;
    }

    public static function addToCart($id, $id_cart) {
        if (!is_null($id_cart))
            if (is_array($id)) {
                if (@$id[0]['ID']) {
                    for ($i = 0; $i < count($id); $i++) {
                        self::addToCart($id[$i]['ID'], $id_cart);
                    }
                } else
                    return false;
            }
            else {
                global $dbCon;
                $id_cart*=1;
                $id*=1;
                $query = "UPDATE `" . $dbCon->get("hostDB") . "`.`files_list` SET `IDcart` = " . $id_cart . " WHERE `files_list`.`ID` = " . $id . ";";
                return $dbCon->exe($query);
            } else
            return false;
    }

    public static function addFile($filename, $filesize) {
        global $dbCon;
        self::loadSettings();

        if (!$iduser = User::getIdUser()) {
            $iduser = NULL;
            $idconn = Connection::getIdConnection();
        } else
            $idconn = NULL;


        $query = "INSERT INTO `" . $dbCon->get("hostDB") . "`.`files_list` (`ID`, `IDconnection`, `IDuser`, `name`, `size`, `timeload`)
		VALUES (NULL, :idconn, :iduser, :filename, :filesize, NOW());";
        $arrayQuery = array(':idconn' => $idconn, ':iduser' => $iduser, ':filename' => $filename, ':filesize' => $filesize);
        if ($res = $dbCon->insertion($query, $arrayQuery))
            return $res;
        else {
            Notification::addWarning("CAR-F0006");
        }
    }

    public static function removeFile($idFile, $restriction = true) {
        global $dbCon;
        self::loadSettings();

        $idFile*=1;
        $query = "SELECT * FROM `files_list` WHERE `ID` = :idfile";
        $arrayQuery = array(":idfile" => $idFile);
        $res = $dbCon->selection($query, $arrayQuery);

        if ($res['IDuser'] == User::getIdUser() || $res['IDconnection'] == Connection::getIdConnection() || !$restriction) {
            $query = "DELETE FROM `" . $dbCon->get("hostDB") . "`.`files_list` WHERE `files_list`.`ID` = " . $idFile;
            if ($dbCon->exeQuery($query)) {
                unlink(self::$location . ($idFile * 1));
                return true;
            } else {
                //todo: gestire eccezioni
            }
        } else {
            //todo: gestire eccezioni
        }
        return false;
    }

    public static function saveFileToLocation($file) {
        self::loadSettings();
        $location = self::$location;

        $fileData['name'] = $file['name'][0];
        $fileData['size'] = $file['size'][0];
        $fileData['error'] = $file['error'][0];
        if ($fileData['error'] == 0) {
            $imageCounter = self::addFile($fileData['name'], $fileData['size']);

            move_uploaded_file($file['tmp_name'][0], $location . $imageCounter);
            return $fileData;
        } else
            return false;
    }

    public static function downloadFile($idFile, $restriction = true) {
        global $dbCon;
        self::loadSettings();
        if ($restriction)
            if (User::getIdUser())
                $query = "SELECT * FROM `files_list` WHERE `ID` = :id AND `IDuser` = :idUser ";
            else
                $query = "SELECT * FROM `files_list` WHERE `ID` = :id AND `IDconnection` = :idConn ";
        else
            $query = "SELECT * FROM `files_list` WHERE `ID` = :id";
        $arryQuery = array(":id" => ($idFile * 1), ":idUser" => User::getIdUser(), ":idConn" => Connection::getIdConnection());
        $res = $dbCon->selection($query, $arryQuery);
        if ($res) {
            forceDownload(self::$location . ($idFile * 1), $res['name']);
            return true;
        } else {
            //todo:gestire eccezione
        }
    }

}
