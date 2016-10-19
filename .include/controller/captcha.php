<?php
if(class_exists("Captcha"))
	Captcha::generate();
else
	header("Location: /");

?>