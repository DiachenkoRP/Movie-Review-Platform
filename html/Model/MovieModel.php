<?php
require_once "Database.php";

class MovieModel extends Database
{
    public function getMovies()
    {
        return $this->select("SELECT * FROM movies");
    }
    public function getGenres($movie_id)
    {
        $query = "SELECT genre FROM movie_genre WHERE movie_id = ?";
        $genres = $this->select($query, [$movie_id]);
        
        return $genres;
    }
    public function addMovie($title, $release_year, $genres)
    {
        $data = [
            'title' => $title,
            'release_year' => $release_year
        ];

        $movie_id = $this->insert("movies", $data); 
    
        foreach ($genres as $genre) {
            $genreData = [
                'movie_id' => $movie_id,
                'genre' => $genre
            ];
            $this->insert("movie_genre", $genreData);
        }
        
        return $movie_id;
    }
    
    public function deleteMovie($movie_id)
    {
        
        $data = [
            'movie_id' => $movie_id
        ];
        
        $this->delete('reviews', $data);
        $this->delete('movie_genre', $data);
        return $this->delete("movies", $data);
    }
}

?>