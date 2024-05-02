<section class="banner">
    <div class="container">
        <div class="banner-img ">
            <div class="banner-overlayer f-white">
                <h1 class="f-bold text-uppercase">
                    <span class="d-block">Twoje zdanie</span>
                    <span class="d-block">liczy się tutaj!</span>
                </h1>
                <a href="/restaurant" class="banner-button button f-20 mx-mobile-auto w-fit-content" title="Restauracje">Restauracje</a>
            </div>
        </div>
    </div>
</section>
<section class="mt-2">
    <div class="container ">
        <h2 class="section-title">Najnowsze</h2>
        <div class="restaurant-list">
            <?php foreach ($restaurants as $restaurant): ?>
                <?php include __DIR__ . '/../includes/restaurantBox.php' ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>