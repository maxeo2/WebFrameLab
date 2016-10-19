    <nav id="nav">
        <ul>
            <li><a class="nav_section" href="<?php echo Pageloader::pageFromTarget("home", false, "it"); ?>">Home</a></li>
            <li><a class="nav_section" href="<?php echo Pageloader::pageFromTarget("about-me", false, "it"); ?>">Su di me</a></li>
            <li><a class="nav_section" href="<?php echo Pageloader::pageFromTarget("skills", false, "it"); ?>">Competenze</a></li>
            <li><a class="nav_section" href="<?php echo Pageloader::pageFromTarget("worksdone", false, "it"); ?>">Lavori Svolti</a></li>
            <li><a href="<?php echo Pageloader::pageFromTarget(Pageloader::getData("target"), false, "en");for ($i = 0; !empty(Pageloader::getData($i)); $i++)echo "/" . Pageloader::getData(0); ?>"><img style="width: 24px;" src="/imgs/flagEN.svg" alt="In Enlish"></a></li>
        </ul>
    </nav>