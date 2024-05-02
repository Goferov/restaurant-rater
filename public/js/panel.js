function publicateElement() {
    const restaurantId = this.dataset.id;
    const btn = this;

    fetch(`/publicateRestaurant/${restaurantId}`)
        .then(function (response) {
            if (response.ok) {
                btn.classList.toggle('no');
            } else {
                console.error('Failed to publicate the restaurant');
            }
        })
        .catch(function (error) {
            console.error('Error:', error);
        });
}

function deleteElement() {
    const restaurantId = this.dataset.id;
    const row = document.querySelector("#row_" + restaurantId);

    if (confirm("Czy na pewno chcesz usunąć tę restaurację?")) {
        fetch(`/deleteRestaurant/${restaurantId}`)
            .then(function (response) {
                if (response.ok) {
                    row.remove();
                } else {
                    console.error('Failed to delete the restaurant');
                }
            })
            .catch(function (error) {
                console.error('Error:', error);
            });
    }
}

document.querySelectorAll('.publicate-btn').forEach(button => {
    button.addEventListener('click', publicateElement);
});

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', deleteElement);
});