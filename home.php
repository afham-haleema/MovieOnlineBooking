<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affa Cinema</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
</body>
</html>


<?php
include 'header.php';
echo "<h1>Now Showing</h1>";

include 'db.php';
$sql = "SELECT movie_id, title, img, genre, duration FROM movies"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='cards'>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='movie-card'>";
        echo "<a href='details.php?movie_id=" .$row["movie_id"] . "'>";
        echo "<img src='" . $row["img"] . "' alt='" . $row["img"] . "' class='movie-image' width='200px'/>";
        echo "<div class='movie-details'>";
        echo "<h3 class='movie-title'>" . $row["title"] . "</h3>";
        echo "<p class='movie-genre'>Genre: " . $row["genre"] . "</p>";
        echo "<p class='movie-duration'>Duration: " . $row["duration"] . " mins</p>";
        echo "</div>";
        echo "</a>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "No movies found!";
}

$conn->close();

include 'footer.php';
?>