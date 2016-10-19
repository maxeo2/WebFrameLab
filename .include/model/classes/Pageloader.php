<?php

class Pageloader {

    private static $data;
    private static $cnt;
    private static $pageSettings;

    //La funzione è lasciata vuota perche così i files inclusi non avranno conflitti con la scope
    private static function include_clean() { //$file_to_include, $require = false
        if (count(func_get_args()) > 1 && func_get_args()[1])
            require(func_get_args()[0]);
        else
            include(func_get_args()[0]);
    }

    public static function load() {
        global $settings;
        self::$pageSettings['languages'] = $settings['languages'];
        self::$pageSettings['location_message_path'] = $settings['location_message_path'];
        self::$pageSettings['include_path']= $settings['include_path'];
        $requestsUrl = explode("?", $_SERVER['REQUEST_URI'])[0];    //Rimuovo richieste in get
        $requestsUrl = explode("/", $requestsUrl);
        $cnt = 1;

        if (empty($requestsUrl[1])) {
            $data['lang'] = @$_SERVER['HTTP_ACCEPT_LANGUAGE'][0] . @$_SERVER['HTTP_ACCEPT_LANGUAGE'][1];
            if (!self::correctLanguage($data['lang']))
                $data['lang'] = "it";
        }
        else {
            if (strpos($requestsUrl[1], 'index') === false) {
                $data['lang'] = $requestsUrl[1];
            } else
                $data['lang'] = "it";
        }

        if (empty($requestsUrl[2])) {
            $data['page'] = "";
        } else {
            $data['page'] = $requestsUrl[2];
            $cnt++;
        }

        for ($i = 3; $i < count($requestsUrl); $i++) {
            if (!empty($requestsUrl[$i])) {
                if ($i % 2) {
                    $data['name'][] = $requestsUrl[$i];
                } else {
                    $data['value'][] = $requestsUrl[$i];
                }
                $cnt++;
            }
        }
        if (count(@$data['name']) > count(@$data['value'])) {
            $data['value'][] = "";
        }


        self::$data = $data;
        self::$cnt = $cnt;
        return $data;
    }

    public static function dataNameCount() {
        return self::$cnt;
    }

    public static function is_name_set($name) {
        if (isset(self::$data['name']))
            foreach (self::$data['name'] as $key => $val) {
                if (self::$data['name'][$key] == $name)
                    return true;
            }
        return false;
    }

    public static function pageList() {
        global $dbCon;
        return $dbCon->multiArrayRes("SELECT * FROM `pages` LIMIT 0,18446744073709551615;");
    }

    public static function path($page = NULL, $lang = NULL) {
        global $dbCon;

        if (is_null($page) && is_null($page)) {
            $page = self::getData('page');
            $lang = self::getData('lang');
        }

        if (empty($page))
            return false;

        $query = "SELECT * FROM `pages` WHERE `name` = :name AND `lang` = :lang";
        $res = $dbCon->selection($query, array(':name' => $page, ':lang' => $lang));
        if (!empty($res)) {
            self::$data['target'] = $res['terget_page'];
            self::$data['metadescription'] = $res['metadescription'];
            return self::$pageSettings['include_path'].$res['path'];
        } else {
            $query = "SELECT * FROM `pages` WHERE `name` = :name AND `lang` = :lang";
            $res = $dbCon->selection($query, array(':name' => $page, ':lang' => "*"));
            if (!empty($res)) {
                self::$data['target'] = $res['terget_page'];
                self::$data['metadescription'] = $res['metadescription'];
                return self::$pageSettings['include_path'].$res['path'];
            } else
                return false;
        }
    }

    public static function b_pg() {
        $page = explode("/", $_SERVER['REQUEST_URI']);
        while (empty($page[count($page) - 1])) {
            unset($page[count($page) - 1]);
        }
        unset($page[count($page) - 1]);
        return implode("/", $page);
    }

    public static function c_pg() {
        $page = explode("/", $_SERVER['REQUEST_URI']);
        while (empty($page[count($page) - 1])) {
            unset($page[count($page) - 1]);
        }
        return implode("/", $page);
    }

    public static function pageFromTarget($targetName, $path = false, $lang = NULL) {
        global $dbCon;
        if ($lang == NULL)
            $lang = self::getData("lang");
        $query = "SELECT * FROM `pages` WHERE `terget_page` = :targetName AND `lang` = :lang";
        $res = $dbCon->selection($query, array(':targetName' => $targetName, ':lang' => $lang));
        if (!empty($res)) {
            if ($path)
                return self::$pageSettings['include_path'].$res['path'];
            else
                return "/" . $lang . "/" . $res['name'];
        } else
            return false;
    }

    public static function includePageFromURL($file_to_include = NULL, $home = "home") {
        if (is_null($file_to_include))
            $file_to_include = self::path();

        if ($file_to_include) {
            if (isset(self::$data['name'][0])) {
                $new_file_to_include = self::additionalPages();
            }

            if (isset($new_file_to_include))
                self::include_clean($new_file_to_include);
            else
                self::include_clean($file_to_include);
            return true;
        }
        elseif ($new_file_to_include = self::additionalPages()) {
            self::include_clean($new_file_to_include);
            return true;
        } else {
            
            if (!self::getData('page')) {
                return self::includeHome($home);
            } else
                return false;
        }
    }

    public static function includeHome($home = "home") {
        global $dbCon;
        $query = "SELECT * FROM `pages` WHERE `terget_page` = :targetName AND `lang` = :lang";
        $res = $dbCon->selection($query, array(':targetName' => $home, ':lang' => self::getData("lang")));
        if ($res) {
            self::$data['target'] = $home;
            self::$data['metadescription'] = $res['metadescription'];
            self::include_clean(self::$pageSettings['include_path'].$res['path']);
            return true;
        } else {
            $res = $dbCon->selection($query, array(':targetName' => $home, ':lang' => "*"));
            if ($res) {
                self::$data['target'] = $home;
                self::$data['metadescription'] = $res['metadescription'];
                self::include_clean(self::$pageSettings['include_path'].$res['path']);
                return true;
            }
            return false; //todo: gestire errore
        }
    }

    public static function additionalPages() {
        for ($i = 0; $i < self::dataNameCount() - 2; $i++) {
            $path = "";
            for ($k = 0; $k < self::dataNameCount() - 2 - $i; $k++) {
                $path.=self::getData($k);
                if ($k < self::dataNameCount() - 3 - $i)
                    $path.="/";
            }
            $path = self::getData('page') . "/" . $path;
            $path_w = self::path($path, self::getData('lang'));
            if ($path_w)
                return $path_w;
        }
    }

    public static function locationToMessage($message,$lang=NULL) {
        if (self::correctLanguage($lang)) {
            header("Location: " . Pageloader::pageFromTarget("report", false,  $lang). "/" . $message);
            return true;
        } else
            return false;
    }

    public static function correctLanguage($lang = NULL) {
        if (is_null($lang))
            $lang = self::getData("lang");
        if (in_array($lang, self::$pageSettings['languages']))
            return true;
        else
            return false;
    }

    public static function getPageSettingsData() {
        return self::$pageSettings;
    }

    public static function addElement($elementName, $lang = NULL) {
        global $dbCon, $settings;
        if (is_null($lang))
            $lang = self::getData("lang");

        $query = "SELECT * FROM `page_elements` WHERE `name` = :name;";
        $res = $dbCon->selection($query, array(':name' => $elementName));

        if (self::correctLanguage() && $res['path']) {

            $newLocation = $settings['base_path_page_elements'].$res['path'];
            if (file_exists(DOCUMENT_ROOT . $newLocation) && is_file(DOCUMENT_ROOT . $newLocation))
                require_once(DOCUMENT_ROOT . $newLocation);
            else {
                if (file_exists($file_to_include = DOCUMENT_ROOT . $newLocation . "/" . $lang . ".php"))
                    require_once($file_to_include);
                else {
                    return false;   //il File non esiste todo:Gestire eccezione
                }
            }
            return true;
        } else
            return false;   //E' stato richiesto un elemento non presente nel db todo: Gestire eccezione
    }

    public static function addTag($tagname, $data, $close = true, $open = true, $echo = true) {
        $txt = "";
        $tab = "";
        $tabul = 0;


        for ($i = 0, $stot = strlen($tagname); $i < $stot; $i++) {
            if ($tagname[0] == ' ') {
                $tabul++;
                $tagname = substr($tagname, 1);
            } else
                break;
        }

        for ($i = 0; $i < $tabul; $i++)
            $tab.="\t";


        if ($open) {
            $txt.=$tab . "<" . $tagname . ">";
        }

        $txt.=$data;
        $tagname = trim($tagname);
        if ($close) {
            $tagname = explode(" ", $tagname);
            $tagname = $tagname[0];
            $txt.=$tab . "</" . $tagname . ">";
        }
        if ($echo)
            echo $txt;
        return $txt;
    }

    public static function ln() {
        echo "\n";
    }

    public static function getData($type) {
        $data = self::$data;
        if ($type === 'target' || $type === 'metadescription'){
            return $data[$type];
        }
        elseif ($type !== 'lang' && $type !== 'page') {
            if (isset($data['name'])) {
                if (is_int($type) && $type < count($data['name']) + count(@$data['value'])) {
                    if ($type % 2)
                        return $data['value'][intval($type / 2)];
                    else
                        return $data['name'][intval($type / 2)];
                } else
                    for ($i = 0; $i < count($data['name']); $i++) {
                        if ($data['name'][$i] == $type) {
                            return $data['value'][$i];
                        }
                    }
                return false;
            }
            return false;
        } else {
            return $data[$type];
        }
    }

}

?>