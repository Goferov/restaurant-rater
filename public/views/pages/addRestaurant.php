<section class="container">
    <h1 class="section-title">Dodaj restaurację</h1>
    <form action="/saveRestaurant" method="post" enctype="multipart/form-data">
        <input class="input py-1 mb-1" placeholder="Nazwa*" type="text" name="name" required>
        <input class="input py-1 mb-1" placeholder="Email" type="email" name="email">
        <input class="input py-1 mb-1" placeholder="Strona internetowa" type="url" name="website">
        <textarea class="input py-1" name="description" rows="5" placeholder="Opis"></textarea>
        <input type="file" class=" py-1 mb-1 f-18"  name="file">

        <h2 class="section-title">Lokalizacja</h2>
        <input class="input py-1 mb-1" placeholder="Ulica*" type="text" name="street" required>
        <input class="input py-1 mb-1" placeholder="Miasto*" type="text" name="city" required>
        <input class="input py-1 mb-1" placeholder="Kod pocztowy*" type="text" name="postalCode" required>
        <input class="input py-1 mb-1" placeholder="Numer budynku*" type="text" name="houseNo" required>
        <input class="input py-1 mb-1" placeholder="Numer mieszkania" type="text" name="apartmentNo">

        <button class="button button-primary w-100 f-medium f-18">Dodaj</button>

    </form>
</section>