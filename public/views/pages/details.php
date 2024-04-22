  <div class="container">
    <section class="restaurant mb-2">
      <div class="main-image d-mobile-none">
          <img src="/public/uploads/<?= $restaurant->getImage() ?>" alt=""/>
      </div>
      <div class="details">
        <h1 class="f-bold mb-1"><?= $restaurant->getName() ?></h1>
        <div class="rate d-flex mb-2">
          <div class="stars">
            <i class="fa-solid fa-star f-yellow"></i>
            <i class="fa-solid fa-star f-yellow"></i>
            <i class="fa-solid fa-star f-yellow"></i>
            <i class="fa-solid fa-star f-yellow"></i>
            <i class="fa-solid fa-star f-yellow"></i>
          </div>
          <div class="f-semibold">4.9/5</div>
        </div>
        <div class="hr mb-2"></div>
        <div class="main-image mb-2 d-none d-mobile-block">
          <img src="/public/uploads/<?= $restaurant->getImage() ?>" alt=""/>
        </div>
        <div class="info mb-2">
          <?= $restaurant->getPhone() ? '<p><i class="fa-solid fa-phone-flip"></i> <a href="tel:'.$restaurant->getPhone().'">'.$restaurant->getPhone().'</a></p>' : '' ?>
          <?= $restaurant->getEmail() ? '<p><i class="fa-solid fa-envelope"></i> <a href="mailto:'.$restaurant->getEmail().'">'.$restaurant->getEmail().'</a></p>' : "" ?>
          <?= $restaurant->getAddress() ? '<p><i class="fa-solid fa-location-dot"></i> '.$restaurant->getAddress().'</p>' : "" ?>
          <?= $restaurant->getWebsite() ? '<p><i class="fa-solid fa-globe"></i> <a href="'.$restaurant->getWebsite().'" target="_blank">'.$restaurant->getWebsite().'</a></p>' : "" ?>
        </div>
          <?= $restaurant->getDescription() ? ' <p class="description f-medium">'.$restaurant->getDescription() .'</p>'  : '' ?>
      </div>
    </section>
    <section>
      <h2 class="section-title d-mobile-none">Opinie użytkowników</h2>
      <div class="opinions-wrap">
        <h2 class="section-title m-mobile-0 d-none d-mobile-block">Opinie użytkowników</h2>
        <div class="opinion-list">
          <div class="opinion-box">
            <div class="user-name d-flex align-items-center mb-1 f-medium">
              User2115
              <span></span>
              <div class="user-rate f-bold">4.9/5</div>
            </div>
            <div class="review ">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce at venenatis massa. Praesent pretium quis risus id vestibulum. Nam pharetra sodales eros, non rhoncus felis. Nunc rhoncus rhoncus sollicitudin. Etiam malesuada consectetur lorem
            </div>
          </div>
        </div>
        <form class="opinion-form">
          <h3 class="section-title">Dodaj opinię</h3>
          <input class="input mb-2 py-1" placeholder="Ocena*" name="rate" type="number" required/>
          <textarea class="input mb-2 py-1" name="review" rows="5" placeholder="Treść*" required></textarea>
          <button class="button button-primary">Dodaj opinie</button>
        </form>
      </div>
    </section>
  </div>
