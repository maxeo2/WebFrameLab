    <footer id="footer">
        <section class="footer_menu">
            <h2>Menu</h2>
            <ul>
                <li><a href="<?php echo Pageloader::pageFromTarget("home", false, "it"); ?>">Home</a></li>
                <li><a href="<?php echo Pageloader::pageFromTarget("about-me", false, "it"); ?>">Su Di Me</a></li>
                <li><a href="<?php echo Pageloader::pageFromTarget("skills", false, "it"); ?>">Competenze</a></li>
                <li><a href="<?php echo Pageloader::pageFromTarget("worksdone", false, "it"); ?>">Lavori Svolti</a></li>
                <li><a href="<?php echo Pageloader::pageFromTarget(Pageloader::getData("target"), false, "en");
    if (!empty(Pageloader::getData(0))) echo "/" . Pageloader::getData(0); ?>"><img style="width: 24px;" src="/imgs/flagEN.svg" alt="In Enlish"></a></li>
            </ul>
        </section>
        <section>
            <h2>Contatto veloce</h2>
            <form class="fast_contact" action="/it/sandMail" method="post">
                <input name="mail" type="email" placeholder="Scrivi qui la tua mail"><br>
                <textarea name="content_txt" placeholder="Scrivi il corpo del messaggio"></textarea>
                <br>
                <ul class="actions">
                    <li><input type="submit" value="Contattami"></li>
                </ul>
            </form>
        </section>
        <section>
            <h2>Contatti</h2>
            <dl class="alt">
                <address>
                    <dt>Indirizzo</dt>
                    <dd>Via Concetto Marchesi 7 &bull; San Giovanni Valdarno, AREZZO 52027 &bull; ITALIA</dd>
                    <dt>Telefono</dt>
                    <dd><a href="tel:+39055046284">(0039) 055-0456284</a></dd>
                    <dd><a href="tel:+393920560787">(0039) 392-0560787</a></dd>
                </address>
                <dt>Email</dt>
                <dd class="mailBoxm"><a href="#" class="mailBoxm"></a><img alt="mail" src="/imgs/infomail.png"></dd>
            </dl>
            <ul class="icons">
                <li><a href="https://fb.com/maxeo90" class="icon fa-facebook alt"><span class="label">Facebook</span></a></li>
                <li><a href="https://twitter.com/maxeo90" class="icon fa-twitter alt"><span class="label">Twitter</span></a></li>
                <li><a href="https://github.com/maxeo" class="icon fa-github alt"><span class="label">GitHub</span></a></li>
            </ul>
        </section>
        <p class="copyright">&copy; Maxeo.it. Design: <a href="https://html5up.net">HTML5 UP</a> e <a href="http://maxeo.it">Maxeo</a>. Animazioni: <a href="http://maxeo.it">Maxeo</a>.</p>
    </footer>