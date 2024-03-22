<?php
require_once "Database.php";

class ReviewModel extends Database
{
    public function getReviews()
    {
        return $this->select("SELECT * FROM reviews");
    }
    public function getAverageRating($movie_id)
    {
        $query = "SELECT ROUND(AVG(rating), 2) AS average_rating FROM reviews WHERE movie_id = :movie_id";
        $parameters = array(':movie_id' => $movie_id);
        $result = $this->select($query, $parameters);
        
        if (!empty($result)) {
            return $result[0]['average_rating'];
        } else {
            return null;
        }
    }
    public function addReview($user_id, $movie_id, $rating, $review_text)
    {
        $data = [
            'user_id' => $user_id,
            'movie_id' => $movie_id,
            'rating' => $rating,
            'review_text' => $review_text
        ];
        return $this->insert('reviews', $data);
    }
    public function deleteReview($review_id)
    {
        $data = [
            'review_id' => $review_id,
        ];
        return $this->delete('reviews', $data);
    }
}
