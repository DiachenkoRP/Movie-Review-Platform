<?php session_start(); ?>
<?php 
    require_once 'src/php/errors.php';
    require_once 'Model/ReviewModel.php';
    $reviewModel = new ReviewModel();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    ?>
    <div class="admin-wrapper">
        <div>
            <a href="index.php">Back to Site</a>
            <?php
                if (!empty($_GET['error']) && isset($errors[$_GET['error']])) {
                    echo $errors[$_GET['error']];
                }
            ?>
        </div>
        <table border=1>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Password Hash</th>
                <th>Role</th>
            </tr>
            <?php 
                require 'Model/UserModel.php';
                $userModel = new UserModel();
                $users = $userModel->getUsers();
            ?>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['password_hash']; ?></td>
                <td><?php echo $user['role']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <form action="src/php/userInterface.php" method="post" name="register">
            <input type="hidden" name="form_action" value="register">

            <label for="username">Username</label>
            <input type="text" name="username"><br>

            <label for="email">Email</label>
            <input type="email" name="email"><br>

            <label for="password">Password</label>
            <input type="password" name="password"><br>

            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password">

            <label for="role">Role</label>
            <input type="text" name="role"><br>

            <input type="submit" value="Register">
        </form>

        <form action="src/php/userInterface.php" method="post">
            <input type="hidden" name="form_action" value="login">

            <label for="username">Username</label>
            <input type="text" name="username"><br>

            <label for="password">Password</label>
            <input type="password" name="password"><br>
            
            <input type="submit" value="Login">
        </form>

        <form action="src/php/userInterface.php" method="post">
            <input type="hidden" name="form_action" value="deleteUser">

            <label for="user_id">User ID</label>
            <input type="number" name="user_id"><br>
            
            <input type="submit" value="Delete">
        </form>
        <form action="src/php/userInterface.php" method="post">
            <input type="hidden" name="form_action" value="logout">
            <input type="submit" value="Login Out">
        </form>
        <h1>Hello, <?php if (isset($_SESSION['username'])) { echo $_SESSION['username']; } else { echo "Guest"; } ?>!</h1>

        <hr>

        <table border=1>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Release Year</th>
                <th>Genres</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>
            <?php 
                require 'Model/MovieModel.php';
                $movieModel = new MovieModel();
                $movies = $movieModel->getMovies();
            ?>
            <?php foreach ($movies as $movie): ?>
            <tr>
                <td><?php echo $movie['movie_id']; ?></td>
                <td><?php echo $movie['title']; ?></td>
                <td><?php echo $movie['release_year']; ?></td>
                <td>
                    <?php 
                    $genres = $movieModel->getGenres($movie['movie_id']);
                    $genreStrings = array_map(function($item) {
                        return $item['genre'];
                    }, $genres);
                    
                    $genresString = implode(', ', $genreStrings);
                    
                    echo $genresString;
                    ?>
                </td>
                <td>
                    <?php 
                        echo $reviewModel->getAverageRating($movie['movie_id']);
                    ?>
                </td>
                <td>
                <a href="make_review.php?movie_id=<?php echo $movie['movie_id']; ?>">Make Review</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <form action="src/php/movieInterface.php" method="post">
            <input type="hidden" name="form_action" value="addMovie">

            <label for="title">Title</label>
            <input type="text" name="title"><br>

            <label for="year">Release Year</label>
            <input type="number" name="year"><br>
            
            <label for="genres">Genres:</label><br>
            <select id="genres" name="genres[]" multiple required>
                <option value="Action">Action</option>
                <option value="Comedy">Comedy</option>
                <option value="Drama">Drama</option>
            </select><br>

            <input type="submit" value="Add Movie">
        </form>

        <form action="src/php/movieInterface.php" method="post">
            <input type="hidden" name="form_action" value="deleteMovie">

            <label for="movie_id">Movie ID</label>
            <input type="number" name="movie_id"><br>
            
            <input type="submit" value="Delete">
        </form>

        <hr>

        <table border=1>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Movie ID</th>
                <th>Rating</th>
                <th>Review Text</th>
                <th>Review Date</th>
            </tr>
            <?php 
                $reviews = $reviewModel->getReviews();
            ?>
            <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?php echo $review['review_id']; ?></td>
                <td><?php echo $review['user_id']; ?></td>
                <td><?php echo $review['movie_id']; ?></td>
                <td><?php echo $review['rating']; ?></td>
                <td><?php echo $review['review_text']; ?></td>
                <td><?php echo $review['review_date']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <form action="src/php/reviewInterface.php" method="post">
            <input type="hidden" name="form_action" value="deleteReview">

            <label for="review_id">Review ID</label>
            <input type="number" name="review_id"><br>
            
            <input type="submit" value="Delete">
        </form>
    </div>
    <?php
}
?>
</body>
</html>