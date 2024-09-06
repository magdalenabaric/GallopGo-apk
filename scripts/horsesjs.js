document.addEventListener("DOMContentLoaded", function () {
    const addHorseBtn = document.getElementById('add-horse-btn');
    const addHorseForm = document.getElementById('add-horse-form');
    const horseForm = document.getElementById('horse-form');
    const horseList = document.getElementById('horse-list');

    addHorseBtn.addEventListener('click', function () {
        addHorseForm.style.display = addHorseForm.style.display === "none" ? "block" : "none";
    });

    function renderHorseItem(horse) {
        const horseItem = document.createElement('div');
        horseItem.classList.add('horse-item');
        horseItem.dataset.id = horse.id;
        horseItem.innerHTML = `
            <div class="horse-img-cont">
                <img src="uploads/${horse.horseImage}" alt="Horse Image" class="horse-image">
            </div>
            <div class="horse-cont-cont">
                <input type="text" class="horse-name-input" value="${horse.horseName}" disabled>
                <p>Age: <input type="number" class="horse-age-input" value="${horse.horseAge}" disabled></p>
                <p>Breed: <input type="text" class="horse-breed-input" value="${horse.horseBreed}" disabled></p>
                <p>Location: <input type="text" class="horse-location-input" value="${horse.location}" disabled></p>
                <p>Terrain: <input type="text" class="horse-terrain-input" value="${horse.terrain}" disabled></p>
                <button class="edit-horse-btn">Edit</button>
                <button class="save-horse-btn" style="display:none;">Save</button>
<button class="delete-horse-btn"><i class="fa fa-trash"></i></button>
            </div>
        `;
        horseList.appendChild(horseItem);

        horseItem.querySelector('.edit-horse-btn').addEventListener('click', function () {
            const inputs = horseItem.querySelectorAll('input');
            inputs.forEach(input => {
                input.disabled = false;
                input.classList.add('editable');
            });
            horseItem.querySelector('.edit-horse-btn').style.display = 'none';
            horseItem.querySelector('.save-horse-btn').style.display = 'block';
        });

        horseItem.querySelector('.save-horse-btn').addEventListener('click', function () {
            const horseNameInput = horseItem.querySelector('.horse-name-input');
            const horseAgeInput = horseItem.querySelector('.horse-age-input');
            const horseBreedInput = horseItem.querySelector('.horse-breed-input');
            const locationInput = horseItem.querySelector('.horse-location-input');
            const terrainInput = horseItem.querySelector('.horse-terrain-input');
            const horseImage = horseItem.querySelector('.horse-image').src.split('/').pop();

            const formData = new FormData();
            formData.append('horseId', horseItem.dataset.id);
            formData.append('horseName', horseNameInput.value);
            formData.append('horseAge', horseAgeInput.value);
            formData.append('horseBreed', horseBreedInput.value);
            formData.append('location', locationInput.value);
            formData.append('terrain', terrainInput.value);
            formData.append('horseImage', horseImage);

            fetch('update_horse.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const inputs = horseItem.querySelectorAll('input');
                        inputs.forEach(input => {
                            input.disabled = true;
                            input.classList.remove('editable');
                        });
                        horseItem.querySelector('.edit-horse-btn').style.display = 'block';
                        horseItem.querySelector('.save-horse-btn').style.display = 'none';
                    } else {
                        console.error('Error updating horse:', data.error);
                    }
                })
                .catch(error => console.error('Error updating horse:', error));
        });

        horseItem.querySelector('.delete-horse-btn').addEventListener('click', function () {
            const horseId = horseItem.dataset.id;

            if (confirm('Are you sure you want to delete this horse?')) {
                fetch('delete_horse.php', {
                    method: 'POST',
                    body: JSON.stringify({ horseId: horseId }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            horseItem.remove();
                        } else {
                            console.error('Error deleting horse:', data.error);
                        }
                    })
                    .catch(error => console.error('Error deleting horse:', error));
            }
        });
    }

    fetch('api/load_horses.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
            } else {
                data.forEach(horse => renderHorseItem(horse));
            }
        })
        .catch(error => console.error('Error loading horses:', error));

    horseForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = new FormData(horseForm);

        fetch('add_horse.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Horse added successfully:', data.horse);
                    renderHorseItem(data.horse);
                    addHorseForm.style.display = 'none';
                    horseForm.reset();
                } else {
                    console.error('Error adding horse:', data.error);
                }
            })
            .catch(error => console.error('Error adding horse:', error));
    });
});
