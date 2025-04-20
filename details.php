
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affa Cinema</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>


<?php
include 'header.php';
include 'db.php';

$movie_id = $_GET['movie_id'] ?? null;

if ($movie_id) {
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE movie_id = :movie_id");
    $stmt->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);
    $stmt->execute();
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    function convertToEmbedURL($url) {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $url, $matches)) {
            return "https://www.youtube.com/embed/" . $matches[1];
        }
        return $url;
    }

    if ($movie) {
        $embedURL = convertToEmbedURL($movie["trailer"]);

        echo "<div class='movie-container'>";
        echo "<h1 class='movie-maintitle'>" . htmlspecialchars($movie["title"]) . "</h1>";
        echo "<div class='movie-media'>";
        echo "<div class='movie-poster'>";
        echo "<img src='" . htmlspecialchars($movie["img"]) . "' alt='" . htmlspecialchars($movie["title"]) . "' />";
        echo "</div>";
        echo "<div class='movie-trailer'>";
        echo "<iframe src='" . $embedURL . "' allowfullscreen></iframe>";
        echo "</div>";
        echo "</div>";
        echo "<div class='movie-maindetails'>";
        echo "<p><strong>Genre:</strong> " . htmlspecialchars($movie["genre"]) . "</p>";
        echo "<p><strong>Duration:</strong> " . htmlspecialchars($movie["duration"]) . " mins</p>";
        echo "<p><strong>Release Date:</strong> " . htmlspecialchars($movie["release_date"]) . "</p>";
        echo "<p class='movie-description'>" . nl2br(htmlspecialchars($movie["description"])) . "</p>";
        echo "</div>";
        echo "<div class='book-now-container'>";
        echo "<a href='seating.php?movie_id=" . $movie["movie_id"] . "' class='book-now-btn'>Book Now</a>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<p>Movie not found.</p>";
    }
} else {
    echo "<p>Invalid movie ID.</p>";
}

include 'footer.php';
?>

</body>
</html>
