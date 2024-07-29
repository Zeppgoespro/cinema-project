<?php

declare(strict_types=1);

namespace App\Models;

class Movie extends Model
{
    protected string $table = 'movies';

    public function getAll(array $filters = [], string $sort = 'title'): array
    {
        $sql = "SELECT movies.*,
                    GROUP_CONCAT(actors.name SEPARATOR ', ') AS actors,
                    genres.name AS genre
                FROM movies
                JOIN genres ON movies.genre_id = genres.id
                JOIN movie_actors ON movies.id = movie_actors.movie_id
                JOIN actors ON movie_actors.actor_id = actors.id";
        ###

        $params = [];

        if (!empty($filters)) {
            $conditions = [];
            if (isset($filters['genre_id'])) {
                $conditions[] = "movies.genre_id = :genre_id";
                $params['genre_id'] = $filters['genre_id'];
            }
            if (isset($filters['actor_id'])) {
                $conditions[] = "movie_actors.actor_id = :actor_id";
                $params['actor_id'] = $filters['actor_id'];
            }
            if ($conditions) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }
        }

        $sql .= " GROUP BY movies.id ORDER BY movies.$sort";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getAllGenres(): array
    {
        $stmt = $this->db->query("SELECT * FROM genres ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getAllActors(): array
    {
        $stmt = $this->db->query("SELECT * FROM actors ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getById(int $id): array
    {
        $sql = "SELECT movies.*, GROUP_CONCAT(actors.name) AS actors, genres.name AS genre
                FROM movies
                JOIN genres ON movies.genre_id = genres.id
                JOIN movie_actors ON movies.id = movie_actors.movie_id
                JOIN actors ON movie_actors.actor_id = actors.id
                WHERE movies.id = :id
                GROUP BY movies.id";
        ###

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    public function create(array $data): bool
    {
        $this->db->beginTransaction();

        $genreId = $this->getGenreIdByName($data['genre']);

        $stmt = $this->db->prepare("INSERT INTO movies (title, genre_id) VALUES (:title, :genre_id)");
        $stmt->execute([
            'title'     => $data['title'],
            'genre_id'  => $genreId
        ]);

        $movieId = $this->db->lastInsertId();

        foreach ($data['actors'] as $actor) {
            $actorId = $this->getActorIdByName($actor);
            if ($actorId) {
                $stmt = $this->db->prepare("INSERT INTO movie_actors (movie_id, actor_id) VALUES (:movie_id, :actor_id)");
                $stmt->execute([
                    'movie_id' => $movieId,
                    'actor_id' => $actorId
                ]);
            }
        }

        return $this->db->commit();
    }

    private function getGenreIdByName(string $name): int
    {
        $stmt = $this->db->prepare("SELECT id FROM genres WHERE name = :name");
        $stmt->execute(['name' => $name]);
        $genre = $stmt->fetch();

        if ($genre) {
            return (int)$genre['id'];
        } else {
            $stmt = $this->db->prepare("INSERT INTO genres (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);
            return (int)$this->db->lastInsertId();
        }
    }

    private function getActorIdByName(string $name): int
    {
        $stmt = $this->db->prepare("SELECT id FROM actors WHERE name = :name");
        $stmt->execute(['name' => $name]);
        $actor = $stmt->fetch();

        if ($actor) {
            return (int) $actor['id'];
        } else {
            $stmt = $this->db->prepare("INSERT INTO actors (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);
            return (int) $this->db->lastInsertId();
        }
    }

    public function update(int $id, array $data): bool
    {
        $this->db->beginTransaction();

        $genreId = $this->getGenreIdByName($data['genre']);

        $stmt = $this->db->prepare("UPDATE movies SET title = :title, genre_id = :genre_id WHERE id = :id");
        $stmt->execute([
            'title'     => $data['title'],
            'genre_id'  => $genreId,
            'id'        => $id
        ]);

        $stmt = $this->db->prepare("DELETE FROM movie_actors WHERE movie_id = :movie_id");
        $stmt->execute(['movie_id' => $id]);

        foreach ($data['actors'] as $actorName) {
            $actorId = $this->getActorIdByName($actorName);
            $stmt = $this->db->prepare("INSERT INTO movie_actors (movie_id, actor_id) VALUES (:movie_id, :actor_id)");
            $stmt->execute(['movie_id' => $id, 'actor_id' => $actorId]);
        }

        return $this->db->commit();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM movies WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
