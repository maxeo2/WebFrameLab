<?php
if (!empty($_POST['mail']) && !empty($_POST['content_txt']) && filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
    $sender_name = $_POST['mail'] . ' by Maxeo.it';
    $sender_mail = $_POST['mail'];
    $target_mail = 'info@maxeo.it';
    $object_mail = 'Fast Contact - Contatto Rapido';
    $body_mail = $_POST['content_txt'];

    //Imposto gli headers
    $mail_headers = "From: " . $sender_name . " <" . $sender_mail . ">\r\n";
    $mail_headers .= "Reply-To: " . $target_mail . "\r\n";
    $mail_headers .= "X-Mailer: PHP/" . phpversion();

    if (@mail($target_mail, $object_mail, $body_mail, $mail_headers))
        Pageloader::locationToMessage("n-email-sent-successfully");
    else
        Pageloader::locationToMessage("n-email-not-sent-sever");
}
else {

    if (empty($_POST['mail']) || empty($_POST['content_txt']))
        Pageloader::locationToMessage("n-email-or-text-box-empty");
    else
        Pageloader::locationToMessage("n-email-is-not-correct");
}