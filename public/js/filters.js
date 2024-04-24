const search = document.querySelector('#search-field');
const restaurantList = document.querySelector('.restaurant-list');

search.addEventListener("keyup", function (event)
{
    if (event.key === "Enter")
    {
        event.preventDefault();

        const data = {search: this.value,  fun: 'search'};

        fetch("/search", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(function (response) {
            return response.json();
        }).then(function (restaurants) {
            console.log(restaurants)
            restaurantList.innerHTML = "";
            loadRestaurants(restaurants)
        });
    }
});

function loadRestaurants(restaurants)  {
    restaurants.forEach(restaurant =>
    {
        createRestaurant(restaurant);
    });
}

function createRestaurant(restaurant) {
    const template = document.querySelector("#restaurant-template");
    const clone = template.content.cloneNode(true);

    const image = clone.querySelector("img");
    image.src = `/public/uploads/${restaurant.image}`;

    const link = clone.querySelector("a");
    link.href = `/restaurant/${restaurant.restaurant_id}`;

    const name = clone.querySelector("h3");
    name.innerHTML = restaurant.name;

    const location = clone.querySelector(".tag-list .location");
    location.innerHTML = restaurant.city;

    const website = clone.querySelector(".tag-list .website");
    website.innerHTML = restaurant.website;

    const rate = clone.querySelector(".rate > div:last-of-type span");
    rate.innerHTML = parseFloat(restaurant.rate).toFixed(1);

    restaurantList.appendChild(clone);
}