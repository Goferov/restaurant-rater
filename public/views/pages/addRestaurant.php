<section class="container">
    <h1 class="section-title">Dodaj restaurację</h1>
    <form action="/saveRestaurant/<?= isset($restaurant) ? ($restaurant->getId()) : ''; ?>" method="post" class="validate-form" enctype="multipart/form-data">
        <div class="mb-1">
            <input class="input py-1" placeholder="Nazwa*" type="text" name="name" required value="<?= isset($restaurant) ? ($restaurant->getName()) : ''; ?>">
        </div>
        <div class="mb-1">
            <input class="input py-1" placeholder="Email" type="email" name="email" value="<?= isset($restaurant) ? ($restaurant->getEmail()) : ''; ?>">
        </div>
        <div class="mb-1">
            <input class="input py-1" placeholder="Telefon" type="text" name="phone" value="<?= isset($restaurant) ? ($restaurant->getPhone()) : ''; ?>">
        </div>
        <div class="mb-1">
            <input class="input py-1" placeholder="Strona internetowa" type="url" name="website" value="<?= isset($restaurant) ? ($restaurant->getWebsite()) : ''; ?>">
        </div>
        <div class="mb-1">
            <textarea class="input py-1" name="description" rows="5" placeholder="Opis"><?= isset($restaurant) ? ($restaurant->getDescription()) : ''; ?></textarea>
        </div>
        <div class="mb-1">
            <input type="file" class=" py-1 f-18" name="file">
        </div>

        <h2 class="section-title">Lokalizacja</h2>
        <div class="mb-1">
            <input class="input py-1" placeholder="Ulica*" type="text" name="street" required value="<?= isset($restaurant) ? ($restaurant->getAddress()->getStreet()) : ''; ?>">
        </div>
        <div class="mb-1">
            <input class="input py-1" placeholder="Miasto*" type="text" name="city" required value="<?= isset($restaurant) ? ($restaurant->getAddress()->getCity()) : ''; ?>">
        </div>
        <div class="mb-1">
            <input class="input py-1" placeholder="Kod pocztowy*" type="text" name="postalCode" required value="<?= isset($restaurant) ? ($restaurant->getAddress()->getPostalCode()) : ''; ?>">
        </div>
        <div class="mb-1">
            <input class="input py-1" placeholder="Numer budynku*" type="text" name="houseNo" required value="<?= isset($restaurant) ? ($restaurant->getAddress()->getHouseNo()) : ''; ?>">
        </div>
        <div class="mb-1">
            <input class="input py-1" placeholder="Numer mieszkania" type="text" name="apartmentNo" value="<?= isset($restaurant) ? ($restaurant->getAddress()->getApartmentNo()) : ''; ?>">
        </div>
        <input type="hidden" name="addressId" value="<?= isset($restaurant) ? ($restaurant->getAddress()->getId()) : ''; ?>">
        <button type="submit" class="button button-primary w-100 f-medium f-18">Dodaj</button>
    </form>
</section>
