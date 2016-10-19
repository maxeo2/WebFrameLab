<?php

if (!empty($_POST['name']) && !empty($_POST['password'])) {
    $user = User::correctLogin($_POST['name'], $_POST['password']);
    if ($user) {
        $idConn = Connection::getIdConnection();
        Connection::removeCurrentConnection();
        
        if (isset($_POST['remember']) && $_POST['remember'])
            Connection::makeNewConnection($user);
        else
            Connection::makeNewConnection($user, true);
        Cart::merge($user, $idConn, $force = 0);
        Pageloader::locationToMessage('n1');
    } else {
        $msg_log = Notification::showCode("n2", Pageloader::getData("lang"), true);
        $_SESSION['msg_log'] = $msg_log['description'];
        header("Location: " . Pageloader::b_pg());
    }
} else {
    header("Location: " . Pageloader::b_pg() . "/errore/non è stato inserito il nome o la password.");
}
//todo: mettere l'errore correttamente
?>