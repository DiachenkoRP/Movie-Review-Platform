<?php
require_once 'src/php/errors.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="src/css/index.css">
    <title>MRP | Make Review</title>
</head>
<body>
    <header>
        <div class="d-flex flex-column justify-content-between align-items-center">
            <h1>MRP</h1>
            <nav>
                <a href="index.php">Home</a>
            </nav>
        </div>
    </header>

    <main>
    <div class="d-flex flex-column review_form">
        <div class="container bg-danger text-white">
            <?php 
                if (!empty($_GET['error']) && isset($errors[$_GET['error']]))
                {
                    echo $errors[$_GET['error']];
                }
            ?>
        </div>
        <form action="src/php/reviewInterface.php" method="post">
            <input type="hidden" name="form_action" value="addReview">
            <input type="hidden" name="movie_id" value="<?php echo $_GET['movie_id']; ?>">
            
            <div class="mb-3">
            <select class="form-select" name="rating">
                <option value="1" selected>1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select>
            </div>

            <div class="mb-3">
                <label for="review_text" class="form-label">Review Text</label>
                <textarea class="form-control"  name="review_text" id="" cols="100" rows="20"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add Review</button>
        </form>
        <a href="index.php">Back to Site</a>
    </div>
    </main>
    <footer>
        <p>&copy Copyright 2024. MRP - Movie Review Platform.</p>
    </footer>
</body>
</html>