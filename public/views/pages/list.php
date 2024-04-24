
  <div class="container">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="section-title mb-0">Filtry</h2>
        <?= $roleId == 1 ? '<a href="/addRestaurant" title="Dodaj restaurację" class="button button-primary">Dodaj restaurację</a>' : '' ?>
    </div>
    <div class="filters-wrapper bg-lgray mb-1">
        <div class="d-flex align-items-center flex-tablet-column align-items-tablet-start">
            <label class="me-1">Sortowanie:</label>
            <select id="order" class="custom-select input" name="order">
                <option value="1">Od najnowszych</option>
                <option value="2">Od najstarszych</option>
                <option value="3">Od A do Z</option>
                <option value="4">Od Z do A</option>
                <option value="5">Od najlepszych</option>
                <option value="6">Od najgorszych</option>
            </select>
        </div>
      <div class="search-field position-relative">
        <input id="search-field" type="search" class="input" placeholder="Szukaj..." name="search"/>
        <i class="fa-solid fa-magnifying-glass f-20"></i>
      </div>

        <div class="d-flex align-items-center justify-content-end flex-tablet-column align-items-tablet-start">
            <button id="filter-btn" class="button button-primary f-18">Filtruj</button>
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
                                <?= $reviewHelper->generateStars($resturant->getRate()) ?>
                            </div>
                            <div class="f-semibold"><?= number_format($resturant->getRate(), 1) ?>/5</div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
  </div>

  <template id="restaurant-template">
      <div>
          <a href="" class="restaurant-box d-block">
              <div class="image">
                  <img src="" alt=""/>
              </div>
              <div class="restaurant-details">
                  <h3></h3>
                  <div class="tag-list">
                      <div><i class="fa-solid fa-location-dot"></i> <span class="location"></span></div>
                      <div><i class="fa-solid fa-globe"></i> <span class="website"></span></div>
                  </div>
                  <div class="rate d-flex ">
                      <div class="stars">
                          <i class="fa-solid fa-star f-yellow"></i>
                          <i class="fa-solid fa-star f-yellow"></i>
                          <i class="fa-solid fa-star f-yellow"></i>
                          <i class="fa-solid fa-star f-yellow"></i>
                          <i class="fa-solid fa-star f-yellow"></i>
                      </div>
                      <div class="f-semibold"><span></span>/5</div>
                  </div>
              </div>
          </a>
      </div>
  </template>

  <script src="/public/js/filters.js"></script>
