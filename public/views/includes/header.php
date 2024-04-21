<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/public/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.2/css/all.css">
    <link rel="icon" type="image/x-icon" href="/public/img/favicon.ico">
    <title>TOPKEBAB - Twoje zdanie liczy się tutaj!</title>
</head>
<body>
<header>
    <div class="top bg-main f-white">
        <div class="container flex-row-between-center">
            <div class="flex-row-center">
                <a href="tel:+48514343443" class="contact-item me-1">
                    <i class="fa-solid fa-phone-flip m-mobile-0"></i> <span class="d-mobile-none">+48 514 343 443</span>
                </a>
                <a href="mailto:redakcja@topkebab.pl" class="contact-item">
                    <i class="fa-solid fa-envelope m-mobile-0"></i> <span class="d-mobile-none">redakcja@topkebab.pl</span>
                </a>
            </div>
            <div class="d-flex">

                <?php if($isLogin): ?>
                    <a href="/panel" class="contact-item">
                        <i class="fa-solid fa-user me-1"></i> <span class="d-mobile-none">Twoje konto</span>
                    </a>
                    <a href="/logout" class="contact-item">
                        <i class="fa-solid fa-right-from-bracket m-mobile-0"></i> <span class="d-mobile-none">Wyloguj się</span>
                    </a>
                <?php else: ?>
                    <a id="login-btn" href="#" class="contact-item open-modal" data-modal="login-modal">
                        <i class="fa-solid fa-user m-mobile-0"></i> <span class="d-mobile-none">Zaloguj się</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="bottom bg-white">
        <div class="container flex-row-between-center">
            <a href="/" title="Home" class="logo">
                <img src="/public/img/logo.svg" alt="TopKebab logo"/>
            </a>
            <nav id="main-menu">
                <div><a href="/" title="Home" class="f-semibold ">Home</a></div>
                <div><a href="/restaurant" title="Restauracje" class="f-semibold ">Restauracje</a></div>
                <div><a href="/contact" title="Kontakt" class="f-semibold ">Kontakt</a></div>
            </nav>
            <div id="menu-opener" class="hamburger hamburger--squeeze d-none d-mobile-block">
                <div class="hamburger-box">
                    <div class="hamburger-inner"></div>
                </div>
            </div>
        </div>
    </div>
</header>