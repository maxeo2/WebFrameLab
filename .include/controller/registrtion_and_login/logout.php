<?php
Connection::removeCurrentConnection();
Connection::makeNewConnection();
session_destroy();
header("Location: /");
?>