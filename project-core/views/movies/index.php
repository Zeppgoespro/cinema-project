<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Library</title>
    <link rel="stylesheet" href="/styles.css">
    <script src="/scripts.js"></script>
</head>
<body>
    <div class="grand-container moviep-grand-container">

        <div class="filter-form">
            <span>Фильтр >>> </span>
            <form action="/movies" method="GET">
                <label for="genre">Жанр:</label>
                <select id="genre" name="genre_id">
                    <option value="">Выберите жанр</option>
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?= $genre['id']; ?>" <?= isset($_GET['genre_id']) && $_GET['genre_id'] == $genre['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($genre['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="actor">Актёр:</label>
                <select id="actor" name="actor_id">
                    <option value="">Выберите актёра</option>
                    <?php foreach ($actors as $actor): ?>
                        <option value="<?= $actor['id']; ?>" <?= isset($_GET['actor_id']) && $_GET['actor_id'] == $actor['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($actor['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="Фильтр">
            </form>
            <a href="/movies" style="margin-left: 26px;">Сбросить фильтр/обновить страницу</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Жанр</th>
                    <th>Актёры</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($movies)): ?>
                    <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td>
                                <a href="/movies/<?= $movie['id']; ?>">
                                    <?= htmlspecialchars($movie['title']); ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($movie['genre']); ?></td>
                            <td><?= htmlspecialchars($movie['actors']); ?></td>
                            <td>
                                <a href="/movies/<?= $movie['id']; ?>">Посмотреть</a>
                                <a href="javascript:void(0);" onclick="deleteMovie(<?= $movie['id']; ?>)">Удалить</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Никаких фильмов не планируется...</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="movie-add-form">
            <form action="/movies" method="POST">
                <label for="f_title">Название фильма:</label>
                <input type="text" id="f_title" name="title" placeholder="Название">
                <label for="f_genre">Жанр фильма:</label>
                <input type="text" id="f_genre" name="genre" placeholder="Жанр">

                <div id="actor-fields" class="actor-fields">
                    <label for="f_actor">Актёр:</label>
                    <input type="text" name="actors[]" class="actor-input" placeholder="Имя актёра">
                </div>

                <button type="button" onclick="addActorField()">+ Актёр</button>

                <input type="submit" value="Добавить">
            </form>
        </div>
    </div>
</body>
</html>
