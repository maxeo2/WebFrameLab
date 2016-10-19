<?php
if (isset($_FILES['files'])) {
    FileManage::saveFileToLocation($_FILES['files']);
}
global $_POST;
if (isset($_POST['download'])) {
    if(!FileManage::downloadFile($_POST['download']))
        ;//todo: gestire eccezione
}
else
if (isset($_POST['delete'])) {
     if(FileManage::removeFile($_POST['delete']))
         echo 1;
}
else{
$json = FileManage::loadFiles();
echo json_encode($json);
}


