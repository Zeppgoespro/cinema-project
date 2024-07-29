<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Movie;
use App\View;

class MovieController
{
    private Movie $model;

    public function __construct()
    {
        $this->model = new Movie();
    }

    public function index(): View
    {
        $filters = [];
        if (isset($_GET['genre_id']) && $_GET['genre_id'] !== '') {
            $filters['genre_id'] = (int)$_GET['genre_id'];
        }
        if (isset($_GET['actor_id']) && $_GET['actor_id'] !== '') {
            $filters['actor_id'] = (int)$_GET['actor_id'];
        }

        $sort = $_GET['sort'] ?? 'title';
        $movies = $this->model->getAll($filters, $sort);
        $genres = $this->model->getAllGenres();
        $actors = $this->model->getAllActors();

        return View::make('movies/index', [
            'movies' => $movies,
            'genres' => $genres,
            'actors' => $actors
        ]);
    }

    public function show(int $id): View
    {
        $movie = $this->model->getById($id);

        if (!$movie) {
            return View::make('error/404');
        }

        //return View::json($movie);
        return View::make('movies/show', ['movie' => $movie]);
    }

    public function store(): string
    {
        $data = [
            'title' => $_POST['title'] ?? '',
            'genre' => $_POST['genre'] ?? '',
            'actors' => $_POST['actors'] ?? []
        ];

        if (empty($data['title']) || empty($data['genre'])) {
            return View::json(['message' => 'Title and genre are required'], 400);
        }

        if ($this->model->create($data)) {
            return View::json(['message' => 'Movie created successfully'], 201);
        }

        return View::json(['message' => 'Failed to create movie'], 500);
    }

    public function update(int $id): string
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['title']) || empty($data['genre'])) {
            return View::json(['message' => 'Нужно заполнить название и жанр'], 400);
        }

        if ($this->model->update($id, $data)) {
            return View::json(['message' => 'Фильм успешно обновлён!']);
        }
        return View::json(['message' => 'Failed to update movie'], 500);
    }

    public function delete(int $id): string
    {
        if ($this->model->delete($id)) {
            return View::json(['message' => 'Фильм успешно удалён!']);
        }
        return View::json(['message' => 'Failed to delete movie'], 500);
    }
}
