<?php
Pageloader::addTag("!DOCTYPE html", NULL, 0, 1);
Pageloader::ln();
Pageloader::addTag('html lang="'.Pageloader::getData("lang").'"', NULL, 0, 1);
Pageloader::ln();
Pageloader::addElement("tHead");
Pageloader::ln();
Pageloader::addTag("body", NULL, 0, 1);
Pageloader::ln();
Pageloader::addTag(' div id="wrapper"', NULL, 0, 1);
Pageloader::ln();
Pageloader::addElement("tHeaderHome");
Pageloader::ln();
Pageloader::addElement("tNavHome");
Pageloader::ln();
Pageloader::addTag(' div id="main"', NULL, 0, 1);
Pageloader::ln();
Pageloader::addElement("tAbout-me");
Pageloader::ln();
Pageloader::addElement("tMainSkills");
Pageloader::ln();
Pageloader::addElement("tMainWorksdone");
Pageloader::ln();
Pageloader::addElement("tFacebook_integration");
Pageloader::ln();
Pageloader::addTag(' div', NULL, 1, 0);
Pageloader::ln();
Pageloader::addElement("tFooter");
Pageloader::ln();
Pageloader::addTag(' div', NULL, 1, 0);

?>