<footer class="f-white">
    <div class="top bg-main ">
        <div class="container flex-row-between-start row col-3 flex-mobile-column align-items-mobile-center">
            <div class="logo-white mb-mobile-4">
                <img src="/public/img/logo-white.svg" alt="TopKebab logo"/>
                <p class="f-14">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fermentum vitae dolor ut cursus. Phasellus tempor congue nunc. Nullam eget vestibulum nibh.</p>
            </div>
            <div class="d-flex justify-content-center mb-mobile-4 align-self-mobile-start">
                <div>
                    <h6>Kontakt</h6>
                    <ul>
                        <li>
                            <a href="#" class="contact-item me-1">
                                <i class="fa-solid fa-phone-flip"></i> +48 514 343 443
                            </a>
                        </li>
                        <li>
                            <a href="#" class="contact-item">
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
                        <li><a href="#" title="Home">Home</a></li>
                        <li><a href="#" title="Restauracje">Restauracje</a></li>
                        <li><a href="#" title="Kontakt">Kontakt</a></li>
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

<div id="login-modal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h3 class="text-uppercase text-center f-semibold">Logowanie</h3>
        <form method="post" action="/login">
            <div class="mb-1">
                <input class="input" type="text" name="login" placeholder="Login" required/>
            </div>
            <div class="mb-1">
                <input class="input" type="password" name="password" placeholder="Hasło" required/>
            </div>
            <div class="mb-1">
                <button class="button button-primary f-24 w-100 f-medium text-uppercase">Zaloguj się</button>
            </div>
            <div>
                <p>Nie masz konta? <a href="#">Załóż konto</a></p>
            </div>
        </form>
    </div>
</div>

</body>
<script src="/public/js/scripts.js"></script>
</html>