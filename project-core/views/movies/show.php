<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($movie['title']); ?></title>
    <link rel="stylesheet" href="/styles.css">
    <script src="/scripts.js"></script>
</head>
<body>
    <div class="grand-container show-grand-container">

        <div class="show-left">
            <h2><?= htmlspecialchars($movie['title']); ?></h2>

            <div>
                <a href="/movies">Вернуться к списку фильмов</a><br/>
                <a href="javascript:void(0);" onclick="deleteMovie(<?= $movie['id']; ?>)">Удалить фильм</a>
            </div>

            <div class="details">
                <p><b>Жанр:</b> <?= htmlspecialchars($movie['genre']); ?></p>
                <p><b>Актёры:</b>
                    <?php
                        $actors = explode(',', $movie['actors']);
                        echo htmlspecialchars(implode(', ', $actors));
                    ?>
                </p>
            </div>
        </div>

        <div class="show-right">
            <div class="form-section">
                <h2>Изменить фильм</h2>
                <form id="update-movie-form" action="/movies/<?= $movie['id']; ?>" method="POST" onsubmit="submitUpdateForm(event)">
                    <label for="f_title">Название фильма:</label><br/>
                    <input type="text" id="f_title" name="title" value="<?= htmlspecialchars($movie['title']); ?>"><br/>
                    <label for="f_genre">Жанр:</label><br/>
                    <input type="text" id="f_genre" name="genre" value="<?= htmlspecialchars($movie['genre']); ?>"><br/>

                    <div id="actor-fields" class="show-actor-fields">
                        <label for="f_actor">Актёры фильма:</label><br/>
                        <?php foreach (explode(',', $movie['actors']) as $actor): ?>
                            <input type="text" name="actors[]" class="actor-input" value="<?= htmlspecialchars($actor); ?>"><br/>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" onclick="addActorField()">+ Актёр</button>

                    <input type="submit" value="Изменить">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
