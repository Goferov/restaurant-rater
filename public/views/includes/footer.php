<footer class="f-white">
    <div class="top bg-main ">
        <div class="container flex-row-between-start row col-3 flex-mobile-column align-items-mobile-center">
            <div class="logo-white mb-mobile-4">
                <a href="/" title="Home"><img src="/public/img/logo-white.svg" alt="TopKebab logo"/></a>
                <p class="f-14">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum vitae dolor ut cursus. Phasellus tempor congue nunc. Nullam eget vestibulum nibh.</p>
            </div>
            <div class="d-flex justify-content-center mb-mobile-4 align-self-mobile-start">
                <div>
                    <h6>Kontakt</h6>
                    <ul>
                        <li>
                            <a href="tel:+48514343443" class="contact-item me-1">
                                <i class="fa-solid fa-phone-flip"></i> +48 514 343 443
                            </a>
                        </li>
                        <li>
                            <a href="mailto:redakcja@topkebab.pl" class="contact-item">
                                <i class="fa-solid fa-envelope"></i> redakcja@topkebab.pl
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-mobile-4 align-self-mobile-start">
                <div>
                    <h6>Menu</h6>
                    <ul>
                        <li><a href="/" title="Home">Home</a></li>
                        <li><a href="/restaurant" title="Restauracje">Restauracje</a></li>
                        <li><a href="/contact" title="Kontakt">Kontakt</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom bg-second f-11 py-1">
        <div class="container flex-row-between-center flex-mobile-column">
            <div>TOPKEBAB © 2024 Wszelkie prawa zastrzeżone </div>
            <div>Realizacja: Marcin Godfryd</div>
        </div>
    </div>
</footer>
<?php if(!$isLogin): ?>
<div id="login-modal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h3 class="text-uppercase text-center f-semibold">Logowanie</h3>
        <form method="post" action="/login">
            <div class="mb-1">
                <input class="input" type="text" name="email" placeholder="Email" required/>
            </div>
            <div class="mb-1">
                <input class="input" type="password" name="password" placeholder="Hasło" required/>
            </div>
            <div class="mb-1">
                <button class="button button-primary f-24 w-100 f-medium text-uppercase">Zaloguj się</button>
            </div>
            <div>
                <p>Nie masz konta? <a href="#" class="open-modal" data-modal="register-modal">Załóż konto</a></p>
            </div>
        </form>
    </div>
</div>

<div id="register-modal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h3 class="text-uppercase text-center f-semibold">Rejestracja</h3>
        <form method="post" action="/register">
            <div class="mb-1">
                <input class="input" type="text" name="name" placeholder="Nazwa" required/>
            </div>
            <div class="mb-1">
                <input class="input" type="email" name="email" placeholder="Email" required/>
            </div>
            <div class="mb-1">
                <input class="input" type="password" name="password" placeholder="Hasło" required/>
            </div>
            <div class="mb-1">
                <input class="input" type="password" name="confirmedPassword" placeholder="Powtórz hasło" required/>
            </div>
            <div class="mb-1">
                <button class="button button-primary f-24 w-100 f-medium text-uppercase">Zarejestruj się</button>
            </div>
            <div>
                <p>Masz już konto? <a href="#" class="open-modal" data-modal="login-modal">Zaloguj się</a></p>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
</body>
<script src="/public/js/scripts.js"></script>
</html>