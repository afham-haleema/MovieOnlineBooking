<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affa Cinema</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
    include 'header.php';
    include 'db.php';
    $movie_id = $_GET['movie_id'];

    $sql = "SELECT * FROM movies WHERE movie_id = '$movie_id'";
    $result = $conn->query($sql);
    $movie = $result->fetch_assoc();

    function convertToEmbedURL($url) {
        // If URL is in format: https://www.youtube.com/watch?v=VIDEO_ID
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $url, $matches)) {
            return "https://www.youtube.com/embed/" . $matches[1];
        }
        return $url; 
    }
    
    $embedURL = convertToEmbedURL($movie["trailer"]);

    if ($movie) {
        echo "<div class='movie-container'>";
        echo "<h1 class='movie-maintitle'>" . $movie["title"] . "</h1>";
        echo "<div class='movie-media'>";
        echo "<div class='movie-poster'>";
        echo "<img src='" . $movie["img"] . "' alt='" . $movie["title"] . "' />";
        echo "</div>";
        echo "<div class='movie-trailer'>";
        echo "<iframe src='" . $embedURL . "' allowfullscreen></iframe>";
        echo "</div>";
        echo "</div>"; 
        echo "<div class='movie-maindetails'>";
        echo "<p><strong>Genre:</strong> " . $movie["genre"] . "</p>";
        echo "<p><strong>Duration:</strong> " . $movie["duration"] . " mins</p>";
        echo "<p><strong>Release Date:</strong> " . $movie["release_date"] . "</p>";
        echo "<p class='movie-description'>" . $movie["description"] . "</p>";
        echo "</div>";
        echo "<div class='book-now-container'>";
        echo "<a href='seating.php?movie_id=" . $movie["movie_id"] . "' class='book-now-btn'>Book Now</a>";
        echo "</div>";
        echo "</div>"; 
    } else {
        echo "<p>Movie not found.</p>";
    }
    include 'footer.php';
?>
</body>
</html>