<?php
session_start();

require_once 'Model/MovieModel.php';
require_once 'Model/ReviewModel.php';
require_once 'src/php/errors.php';

$movieModel = new MovieModel();
$reviewModel = new ReviewModel();
$movies = $movieModel->getMovies();

?>
<?php
$db = new Database();

$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$recordsPerPage = 16;

$paginationData = $db->getPaginationData('movies', $currentPage, $recordsPerPage);
$totalRecords = $paginationData['totalRecords'];
$totalPages = $paginationData['totalPages'];
$movies = $paginationData['data'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="src/css/index.css">
    <title>MRP | Home</title>
</head>
<body>
    <header>
        <div class="d-flex flex-column justify-content-between align-items-center">
            <h1>MRP</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="admin.php">Admin Page</a>
            </nav>
        </div>
    </header>

    <main>
    <div class="auth_form <?php echo (isset($_SESSION['username'])) ? 'd-none' : ''; ?>">
        <div class="container mt-5">
            <div class="container bg-danger text-white">
                <?php 
                    if (!empty($_GET['error']) && isset($errors[$_GET['error']]))
                    {
                        echo $errors[$_GET['error']];
                    }
                ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <form action="src/php/userInterface.php" method="post">
                        <input type="hidden" name="form_action" value="register">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password">
                        </div>

                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>

                <div class="col-md-6">
                    <form action="src/php/userInterface.php" method="post">
                        <input type="hidden" name="form_action" value="login">

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>

                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <div class="movies_block <?php echo (!isset($_SESSION['username'])) ? 'd-none' : ''; ?>">
        <table class="table table-striped table-bordered custom-table text-center">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Release Year</th>
                    <th>Genres</th>
                    <th>Average Rating</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movies as $movie): ?>
                <tr>
                    <td><?php echo $movie['title']; ?></td>
                    <td><?php echo $movie['release_year']; ?></td>
                    <td>
                    <?php 
                        $genres = $movieModel->getGenres($movie['movie_id']);
                        $genreStrings = array_map(function($item) { return $item['genre']; }, $genres);
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
                        <a href="make_review.php?movie_id=<?php echo $movie['movie_id']; ?>" class="btn btn-primary">Make Review</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination justify-content-start">
                <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                    <li class="page-item <?php echo ($currentPage == $page) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <?php if (!isset($_SESSION['username']))?>
    <aside class="<?php echo (!isset($_SESSION['username'])) ? 'd-none' : ''; ?>">
        <h3>Hello, <?php echo (isset($_SESSION['username'])) ? $_SESSION['username'] : "Guest" ;?>!</h3>
        <?php if(isset($_SESSION['username'])) { ?>
            <form action="src/php/userInterface.php" method="post">
                <input type="hidden" name="form_action" value="logout">
                <input type="submit" value="Logout">
            </form>
        <?php } ?>
    </aside>
    </main>
    <footer>
        <p>&copy Copyright 2024. MRP - Movie Review Platform.</p>
    </footer>
</body>
</html>