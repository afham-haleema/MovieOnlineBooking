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
echo "<h1>Now Showing</h1>";

include 'db.php';

try {
    $sql = "SELECT movie_id, title, img, genre, duration FROM movies"; 
    $stmt = $pdo->query($sql); // No need to prepare if no parameters

    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($movies) > 0) {
        echo "<div class='cards'>";
        foreach ($movies as $row) {
            echo "<div class='movie-card'>";
            echo "<a href='details.php?movie_id=" . htmlspecialchars($row["movie_id"]) . "'>";
            echo "<img src='" . htmlspecialchars($row["img"]) . "' alt='" . htmlspecialchars($row["title"]) . "' class='movie-image' width='200px'/>";
            echo "<div class='movie-details'>";
            echo "<h3 class='movie-title'>" . htmlspecialchars($row["title"]) . "</h3>";
            echo "<p class='movie-genre'>Genre: " . htmlspecialchars($row["genre"]) . "</p>";
            echo "<p class='movie-duration'>Duration: " . htmlspecialchars($row["duration"]) . " mins</p>";
            echo "</div>";
            echo "</a>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "No movies found!";
    }

} catch (PDOException $e) {
    echo "Error fetching movies: " . $e->getMessage();
}

include 'footer.php';
?>

</body>
</html>
