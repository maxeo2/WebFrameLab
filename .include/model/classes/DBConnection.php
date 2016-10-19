<?php

class DBConnection extends PDO {

    private $hostName;
    private $hostUser;
    private $hostPassword;
    private $hostDB;
    private $typeDB;
    private $conectionActivate = false;

    public function __construct() {
        global $settings;

        $this->hostName = $settings['hostName'];
        $this->hostUser = $settings['hostUser'];
        $this->hostPassword = $settings['hostPassword'];
        $this->hostDB = $settings['hostDB'];
        $this->typeDB = $settings['typeDB'];

        $parameters = func_get_args(); //consente di creare una connessione passando dei parametri
        $connection_parameters = array(self::ATTR_PERSISTENT => true);

        if (isset($parameters[0])) {
            $this->hostName = $parameters[0];
            if (isset($parameters[1])) {
                $this->hostUser = $parameters[1];
                if (isset($parameters[2])) {
                    $this->hostPassword = $parameters[2];
                    if (isset($parameters[3])) {
                        $this->hostDB = $parameters[3];
                        if (isset($parameters[4])) {
                            $connection_parameters = $parameters[4];
                        }
                    }
                }
            }
        }

        //Connette al database
        try {
            parent::__construct($this->typeDB . ":host=" . $this->hostName . ";dbname=" . $this->hostDB, $this->hostUser, $this->hostPassword, $connection_parameters);

            $ps = $this->prepare("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
            $ps->execute();
            $this->conectionActivate = true;
        } catch (PDOException $e) {
            Notification::addAlarm(1,$e);
        }
    }

    function countElements($table, $search, $where) {
        $query = "SELECT COUNT(*) FROM `" . $table . "` WHERE `" . $where . "` = '" . $search . "' ";
        return $this->query($query)->fetchColumn();
    }

    function isConnected() {
        return $this->conectionActivate;
    }

    //crea un array multidimensionale con i risultati
    function multiArrayRes($query) {
        $ps = $this->prepare($query);
        $ps->execute();
        return $ps->fetchAll();
    }

    //come il precedente ma solo la prima riga
    function arrayRes($query) {
        $ps = $this->prepare($query);
        $ps->execute();
        return $ps->fetch();
    }

    //ritorna il numero di righe selezionate
    function exeQuery($query) {
        $ps = $this->prepare($query);
        if ($ps->execute())
            ;
        return $ps->rowCount();
    }

    //ritorna l'ID dell'elemento appena inserito
    function exeQueryToGetID($query) {
        $ps = $this->prepare($query);
        if ($ps->execute())
            ;
        return $this->lastInsertId();
    }

    //eregue una queri e restituisce true in caso di esito positivo
    function exe($query) {
        $ps = $this->prepare($query);
        return $ps->execute();
    }

    function updateElemTable($idToUpdate, $tableName, $columnName, $newData, $primaryKey = 'ID') {
        $query = "UPDATE `" . $this->hostDB . "`.`" . $tableName . "` SET `" . columnName . "` = :newdata WHERE `cart`.`" . $primaryKey . "` = " . ($idToUpdate * 1) . ";";
        $ps = $this->prepare($query, array(":newdata" => $newData));
        return $ps->execute();
    }

    //crea un array con gli IDentificativi dei risultati 
    function arrayFromQuery($query, $keyRec = 'ID') {
        $res = array();
        $stat = $this->prepare($query);
        $stat->execute();
        $q_res = $stat->fetchAll();
        for ($i = 0; $i < count($q_res); $i++)
            $res[] = $q_res[$i][$keyRec];
        return $res;
    }

    //inserimento tramite standard pdo
    function insertion($query, $arrayKey = array()) {
        $res = $this->prepare($query);
        $arrayKey = self::array4query($query, $arrayKey);
        if ($res->execute($arrayKey))
            return $this->lastInsertId();
    }

    //select tramite standard pdo usando fetch (prima riga)
    function selection($query, $arrayKey = array()) {
        $res = $this->prepare($query);
        $arrayKey = self::array4query($query, $arrayKey);
        if ($res->execute($arrayKey))
            return $res->fetch();
    }

    // tramite standard pdo usando fetchAll (tutte le righe)
    function selectionAll($query, $arrayKey = array()) {
        $res = $this->prepare($query);
        $arrayKey = self::array4query($query, $arrayKey);
        if ($res->execute($arrayKey))
            return $res->fetchAll();
    }

    //pulisce l'array da eventuali valori "vuoti"
    public static function array4query($query, $arrayKey) {
        foreach ($arrayKey as $key => $val) {
            if (!strpos($query, $key . " ") && !strpos($query, $key . ",") &&
                    !strpos($query, $key . ")") && !strpos($query, $key . ";") &&
                    !(strpos($query, $key) == strlen($query) - strlen($key)))
                unset($arrayKey[$key]);
        }
        return $arrayKey;
    }

    //metodo get generale
    public function get($cosa) {
        switch ($cosa) {
            case 'hostDB':
                return $this->hostDB;
            case 'hostName':
                return $this->hostName;
            case 'hostPassword':
                return $this->hostPassword;
            case 'hostUser':
                return $this->hostUser;
        }
    }

}

?>