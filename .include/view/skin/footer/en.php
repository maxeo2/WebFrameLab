    <footer id="footer">
        <section class="footer_menu">
            <h2>Menu</h2>
            <ul>
            <li><a href="<?php echo Pageloader::pageFromTarget("home", false, "en"); ?>">Home</a></li>
            <li><a href="<?php echo Pageloader::pageFromTarget("about-me", false, "en"); ?>">About Me</a></li>
            <li><a href="<?php echo Pageloader::pageFromTarget("skills", false, "en"); ?>">Skills</a></li>
            <li><a href="<?php echo Pageloader::pageFromTarget("worksdone", false, "en"); ?>">Works Done</a></li>
            <li><a href="<?php echo Pageloader::pageFromTarget(Pageloader::getData("target"), false, "it");if (!empty(Pageloader::getData(0))) echo "/" . Pageloader::getData(0); ?>"><img style="width: 24px;" src="/imgs/flagIT.svg" alt="In Italiano"></a></li>
        </ul>
        </section>
        <section>
            <h2>Fast contact</h2>
            <form class="fast_contact" action="/en/sandMail" method="post">
                <input name="mail" type="email" placeholder="Write your email address"><br>
                <textarea name="content_txt" placeholder="Write the body of the message"></textarea>
                <br>
                <ul class="actions">
                    <li><input type="submit" value="Contact me"></li>
                </ul>
            </form>
        </section>
        <section>
            <h2>Contacts</h2>
            <dl class="alt">
                <address>
                <dt>Address</dt>
                    <dd>7 Via Concetto Marchesi &bull; San Giovanni Valdarno, AREZZO 52027 &bull; ITALY</dd>
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
        <p class="copyright">&copy; Maxeo.it. Design: <a href="https://html5up.net">HTML5 UP</a> and <a href="http://maxeo.it">Maxeo</a>. Animations: <a href="http://maxeo.it">Maxeo</a>.</p>
    </footer>