<?php

class Notification {

    public static $alarm = array();
    public static $warning = array();
    public static $notice = array();
    public static $message = array();
    public static $n_errors = 0;
    public static $str_log = "";

    public static function showCode($code, $lang = 'it', $likeArray = false) {
        if (is_null($lang))
            $lang = Pageloader::getData("lang");
        if (is_numeric($code) && $code[0] !== 0) {
            $static_list = explode("\n", file_get_contents(DOCUMENT_SETTINGS . "lang/" . $lang . "/error.ini"));
            if ($likeArray) {
                return array('subject' => 'db_error', 'functionality' => 'no_db', 'description' => $static_list[$code - 1]);
            } else
                return $static_list[$code - 1];
        }
        else {
            global $dbCon;
            $query = "SELECT * FROM `notices` WHERE `code` = :code AND `lang` = :lang ";
            $code = $dbCon->selection($query, array(':code' => $code, ':lang' => $lang));
            if (!empty($code)) {
                if ($likeArray)
                    return array('subject' => $code['subject'], 'functionality' => $code['functionality'], 'description' => $code['description']);
                else
                    return $code['subject'] . " - " . $code['functionality'] . " - " . $code['description'];
            }
        }
    }

    public static function addAlarm($error_code, $additional_var = null) {
        self::$alarm[] = $error_code;
        self::$n_errors++;
        $mess = self::showCode($error_code);
        self::update_str_log($error_code);
        self::writeLog($GLOBALS, self::$str_log, $additional_var);
        //exit($mess);
    }

    public static function addWarning($error_code) {
        self::$warning[] = $error_code;
        self::update_str_log($error_code);
        self::$n_errors++;
    }

    public static function addNotice($error_code) {
        self::$notice[] = $error_code;
        self::update_str_log($error_code);
    }

    public static function addMessage($error_code) {
        self::$message[] = $error_code;
        self::update_str_log($error_code);
    }

    public static function update_str_log($error_code) {
        $mess = self::showCode($error_code);
        self::$str_log.=$mess . "\n";
    }

    public static function writeLog() {
        global $settings;
        
        $additional_var = func_get_args();
        
        if (self::$n_errors > 0) {
            if (!empty(self::$alarm)) {
                $clientIP = htmlspecialchars($_SERVER["REMOTE_ADDR"], ENT_QUOTES);
                $errorPage = htmlspecialchars($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], ENT_QUOTES);
                $lang = htmlspecialchars($_SERVER['HTTP_ACCEPT_LANGUAGE'][0] . $_SERVER['HTTP_ACCEPT_LANGUAGE'][1], ENT_QUOTES);
                $browserInfo = htmlspecialchars($_SERVER["HTTP_USER_AGENT"], ENT_QUOTES);
                if (!empty(self::$alarm))
                    $error['alarm'] = self::$alarm;
                if (!empty(self::$warning))
                    $error['warning'] = self::$warning;
                if (!empty(self::$notice))
                    $error['notice'] = self::$notice;
                if (!empty(self::$message))
                    $error['message'] = self::$message;
                $error = var_dump_ret($error);
                $additional_var = var_dump_ret($additional_var);

                $msg = "Report: <b>" . date("d/m/Y H:i:s", time()) . "</b><br>\n";
                $msg.="IP client: <b>" . $clientIP . "</b><br>\n";
                $msg.="Page error: <b>" . $errorPage . "</b><br>\n";
                if (User::getIdUser())
                    $msg.="User ID: <b>" . User::getIdUser() . "</b><br>\n";
                $msg.="ID Connection: <b>" . @Connection::getIdConnection() . "</b><br>\n";
                $msg.="Error: <ul>" . self::$str_log . "</ul><br>\n";

                $msg.="<div class='bhs'>Show \$GLOBALS an error codes</div><div class='hs'>" . $error . "<br>" . $additional_var . "</div>";
                
                if(!file_exists(DOCUMENT_ROOT . $settings['log_error_location']))
                    saveFile (file_get_contents ($settings['log_error_location_data']), DOCUMENT_ROOT . $settings['log_error_location']);
                
                $fp = @fopen(DOCUMENT_ROOT . $settings['log_error_location'], 'a');
                fputs($fp, $msg . "<br><br><br>\n\n\n");
                fclose($fp);
            }
            else {
                global $dbCon;
                $logType = "";
                if (!empty(self::$message)) {
                    $error['message'] = self::$message;
                    $logType = "message";
                }
                if (!empty(self::$notice)) {
                    $error['notice'] = self::$notice;
                    $logType = "notice";
                }
                if (!empty(self::$warning)) {
                    $error['warning'] = self::$warning;
                    $logType = "warning";
                }




                $report['error'] = var_dump_ret($error);
                $report['var'] = var_dump_ret($additional_var);
                $report = json_encode($report);


                $query = "INSERT INTO `etichette_online_db`.`logs_data` (`ID`, `log_time`, `log_type`, `notice`, `data_var`) VALUES (NULL, NOW(), :log_type, :notice, :report);";
                $arrayKey = array(":log_type" => $logType, ":notice" => self::$str_log, ":report" => $report);
                $dbCon->insertion($query, $arrayKey);
            }
        }
    }

}

?>