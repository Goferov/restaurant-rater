<div>
    <a href="/restaurant/<?= $restaurant->getId() ?>" class="restaurant-box d-block">
        <div class="image">
            <img src="<?= $restaurant->getImage() ? '/public/uploads/' . $restaurant->getImage() : '/public/img/placeholder.png' ?>" alt="<?= $restaurant->getName() ?>"/>
        </div>
        <div class="restaurant-details">
            <h3><?= htmlentities($restaurant->getName()) ?></h3>
            <div class="tag-list">
                <div><i class="fa-solid fa-location-dot"></i> <span class="location"><?= htmlentities($restaurant->getAddress()->getCity()) ?></span></div>
                <?= $restaurant->getWebsite() ? '<div><i class="fa-solid fa-globe"></i> <span class="website">'.$restaurant->getWebsite().'</span></div>' : '' ?>
            </div>
            <div class="rate d-flex ">
                <div class="stars">
                    <?= $reviewHelper->generateStars($restaurant->getRate()) ?>
                </div>
                <div class="f-semibold"><?= number_format($restaurant->getRate(), 1) ?>/5.0</div>
            </div>
        </div>
    </a>
</div>