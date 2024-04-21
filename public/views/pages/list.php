
  <div class="container">
    <h2 class="section-title">Filtry</h2>
    <div class="filters-wrapper bg-lgray mb-1">
      <div class="search-field position-relative">
        <input type="search" class="input" placeholder="Szukaj..." name="search"/>
        <i class="fa-solid fa-magnifying-glass f-20"></i>
      </div>
      <div class="d-flex align-items-center justify-content-center flex-tablet-column align-items-tablet-start">
        <label class="me-1">Miasto:</label>
        <input class="input" placeholder="Dowolne" name="city">
      </div>
      <div class="d-flex align-items-center justify-content-end flex-tablet-column align-items-tablet-start">
        <label class="me-1">Sortowanie:</label>
        <select class="custom-select input" name="order">
          <option value="1">Od najnowszych</option>
          <option value="2">Od najstarszych</option>
          <option value="3">Alfabetycznie</option>
        </select>
      </div>
    </div>
    <h2 class="section-title">Restauracje</h2>
    <div class="restaurant-list">
        <?php foreach ($restaurants as $resturant): ?>
            <div>
                <a href="/restaurant/<?= $resturant->getId() ?>" class="restaurant-box d-block">
                    <div class="image">
                        <img src="/public/uploads/<?= $resturant->getImage() ?>" alt=""/>
                    </div>
                    <div class="restaurant-details">
                        <h3><?= $resturant->getName() ?></h3>
                        <div class="tag-list">
                            <div><i class="fa-solid fa-location-dot"></i> <?= $resturant->getAddress()->getCity() ?></div>
                            <div><i class="fa-solid fa-globe"></i> <?= $resturant->getWebsite() ?></div>
                        </div>
                        <div class="rate d-flex ">
                            <div class="stars">
                                <i class="fa-solid fa-star f-yellow"></i>
                                <i class="fa-solid fa-star f-yellow"></i>
                                <i class="fa-solid fa-star f-yellow"></i>
                                <i class="fa-solid fa-star f-yellow"></i>
                                <i class="fa-solid fa-star f-yellow"></i>
                            </div>
                            <div class="f-semibold">4.9/5</div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
  </div>
