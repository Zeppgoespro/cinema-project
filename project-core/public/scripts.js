/* AJAX на обновление фильма */
function submitUpdateForm(event) {
    event.preventDefault();
    var form = document.getElementById('update-movie-form');
    var formData = new FormData(form);
    var data = Object.fromEntries(formData.entries());
    var actors = formData.getAll('actors[]');
    data['actors'] = actors;

    fetch(form.action, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.message === 'Фильм успешно обновлён!') {
            window.location.reload();
        }
    });
}

/* AJAX для удаления фильма */
function deleteMovie(id) {
    if (confirm("Точно удалить?")) {
        fetch(`/movies/${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.message === 'Фильм успешно удалён!') {
                window.location.href = '/movies';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

/* Добавление нового поля в формах */
function addActorField() {
    var container = document.getElementById('actor-fields');
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'actors[]';
    input.placeholder = 'Имя актёра';
    input.className = 'actor-input';
    container.appendChild(input);
}
