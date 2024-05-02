
  <section class="admin-panel container">
    <h1 class="section-title">Twoje konto</h1>
    <div class="user-panel">
        <?php require_once __DIR__ . '/../includes/panelNav.php'?>
      <div class="content">
        <?= $message ? '<p class="f-18 mb-2 f-semibold">'.$message.'</p>' : '' ?>
        <form class="user-form f-medium" action="/changePassword" method="post">
          <input class="input py-1" placeholder="Aktualne hasło" type="password" name="currentPassword" required>
          <input class="input py-1" placeholder="Nowe hasło" type="password" name="newPassword" required>
          <input class="input py-1" placeholder="Powtórz hasło" type="password" name="repeatNewPassword" required>
          <button class="button button-primary w-100 f-medium f-18">Zmień hasło</button>
        </form>
      </div>
    </div>
  </section>
