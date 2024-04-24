const search = document.querySelector('#search-field');
const restaurantList = document.querySelector('.restaurant-list');
const filterBtn = document.querySelector('#filter-btn');
const orderSelect = document.querySelector('#order');


filterBtn.addEventListener("click", filter)

function filter() {
    const data = {search: search.value,  fun: 'search', order: orderSelect.value};

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

    const fixedRate = restaurant.rate ? parseFloat(restaurant.rate).toFixed(1) : 0;
    const rate = clone.querySelector(".rate > div:last-of-type span");
    rate.innerHTML = fixedRate;

    const stars = clone.querySelector('.rate .stars');
    stars.innerHTML = generateStars(fixedRate);

    restaurantList.appendChild(clone);
}

function generateStars(rate) {
    const roundedRate = Math.round(rate);
    let starsHTML = '';

    for (let i = 1; i <= 5; i++) {
        if (i <= roundedRate) {
            starsHTML += '<i class="fa-solid fa-star f-yellow"></i>\n';
        } else {
            starsHTML += '<i class="fa-solid fa-star f-lgray"></i>\n';
        }
    }

    return starsHTML;
}