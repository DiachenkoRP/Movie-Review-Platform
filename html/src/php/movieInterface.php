<?php
require '../../Model/MovieModel.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $_SESSION['prev_page'] = explode('?', $_SERVER['HTTP_REFERER']);
    $prev_pager = $_SESSION['prev_page'][0];

    $movieModel = new MovieModel();

    switch ($_POST['form_action'])
    {
        case 'addMovie':
            $movieModel->addMovie($_POST['title'], $_POST['year'], $_POST['genres']);
            header("Location: " .$prev_pager);
            break;
        case 'deleteMovie':
            $movies = $movieModel->getMovies();

            $movieFound = false;
            foreach ($movies as $movie) {
                if ($movie['movie_id'] == $_POST['movie_id']) {
                    $movieFound = true;
                    $movieModel->deleteMovie($_POST['movie_id']);
                    header("Location: " .$prev_pager);
                    break;
                } 
            }
            if (!$movieFound) { header("Location: " .$prev_pager ."?error=1014"); }
            break;
    }
}
?>
