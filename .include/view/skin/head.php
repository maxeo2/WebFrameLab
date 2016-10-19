<head>
    <title><?php
        $thisPgTarget = Pageloader::getData("target");
        $pageTitle = "Matteo Burbui - Maxeo.it";
        if (!empty($thisPgTarget) && $thisPgTarget != "home")
            $pageTitle.= " - " . ucwords(str_replace("-", " ", Pageloader::getData("page")));
        echo $pageTitle;
    ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />


    <link rel="apple-touch-icon" sizes="57x57" href="/imgs/icon/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/imgs/icon/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/imgs/icon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/imgs/icon/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/imgs/icon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/imgs/icon/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/imgs/icon/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/imgs/icon/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/imgs/icon/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/imgs/icon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/imgs/icon/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/imgs/icon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/imgs/icon/manifest.json">
    <link rel="mask-icon" href="/imgs/icon/safari-pinned-tab.svg" color="#d55b5b">
    <meta name="msapplication-TileColor" content="#603cba">
    <meta name="msapplication-TileImage" content="/imgs/icon/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="/style/main.css" />
    <link rel="stylesheet" href="/style/max.css" />
    <?php
    if (Pageloader::getData("lang") == "en")
        $seo_alternate_lang = "it";
    else
        $seo_alternate_lang = "en";
    $seo_alternate_href = Pageloader::pageFromTarget(Pageloader::getData("target"), false, $seo_alternate_lang);
    for ($i = 0; !empty(Pageloader::getData($i)); $i++)
        $seo_alternate_href.="/" . Pageloader::getData(0);

    if (@explode("/", $_SERVER['REQUEST_URI'])[1] != Pageloader::getData("lang")) {
        $seo_alternate_href = "/";
        $seo_alternate_lang = "x-default";
    }
    if (Pageloader::getData("metadescription")) {
        echo '<meta name="description" content="' . Pageloader::getData("metadescription") . '">' . "\n";
        echo '<meta property="og:description" content="' . Pageloader::getData("metadescription") . '">' . "\n";
    }
    ?>
    <meta property="og:type" content="site">
    <meta property="og:title" content="<?php echo $pageTitle; ?>">
    <meta property="og:url" content="http://www.maxeo.it">
    <meta property="og:image" content="http://www.maxeo.it/imgs/me.jpg">
    <meta property="og:site_name" content="Matteo Burbui - Maxeo.it">
    <link rel="alternate" href="<?php echo $seo_alternate_href; ?>" hreflang="<?php echo $seo_alternate_lang; ?>" type=text/html>
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <script src="/scripts/analytics.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script src="/scripts/main.js" type="text/javascript"></script>
</head>