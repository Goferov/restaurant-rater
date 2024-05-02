<ul class="user-menu">
    <li><a href="/panel" class="" title="Zmiana hasła"><i class="fa-solid fa-key"></i> Zmiana hasła</a></li>
    <?= $isAdmin ? '<li><a href="/restaurantList" class="" title="Zmiana hasła"><i class="fa-solid fa-list"></i> Lista restauracji</a></li>' : '' ?>
    <li><a href="/logout" class="f-red" title="Wyloguj się"><i class="fa-solid fa-right-from-bracket"></i> Wyloguj się</a></li>
</ul>