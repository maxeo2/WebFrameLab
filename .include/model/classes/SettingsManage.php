<?php
class SettingsManage {
    private static $pageSettings;
    
    private static function loadPagesettings(){
        self::$pageSettings=  Pageloader::getPageSettingsData();
    }
    
    public static function etitPageSettingsData($value){
        saveFile(json_encode($value), (DOCUMENT_SETTINGS . "pages.json"));
    }
}
?>