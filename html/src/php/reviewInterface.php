<?php
require '../../Model/ReviewModel.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $_SESSION['prev_page'] = explode('?', $_SERVER['HTTP_REFERER']);
    $prev_pager = $_SESSION['prev_page'][0];

    $reviewModel = new ReviewModel();

    switch ($_POST['form_action'])
    {
        case 'addReview':
            if (!empty($_SESSION['user_id']) && !empty($_POST['movie_id']) && !empty($_POST['rating']) && !empty($_POST['review_text'])) {
                $query = "SELECT COUNT(*) FROM reviews WHERE user_id = :user_id AND movie_id = :movie_id";
                $params = array('user_id' => $_SESSION['user_id'], 'movie_id' => $_POST['movie_id']);
                $count = $reviewModel->select($query, $params);
                if ($count[0]['count'] == 0){
                    $reviewModel->addReview($_SESSION['user_id'], $_POST['movie_id'], $_POST['rating'], $_POST['review_text']);
                    header("Location: " .$prev_pager . "?movie_id=" .$_POST['movie_id']);
                } else { header("Location: " .$prev_pager ."?movie_id=" . $_POST['movie_id'] ."&error=1016"); }
            } else { header("Location: " .$prev_pager ."?movie_id=" . $_POST['movie_id'] ."&error=1015"); }
            break;

        case 'deleteReview':
            $reviews = $reviewModel->getReviews();

            $reviewFound = false;
            foreach ($reviews as $review) {
                if ($review['review_id'] == $_POST['review_id']) {
                    $reviewFound = true;
                    $reviewModel->deleteReview($_POST['review_id']);
                    header("Location: " .$prev_pager);
                    break;
                }
            }
            if (!$reviewFound) { header("Location: " .$prev_pager ."&error=1013"); }
            break;
    }
}
?>