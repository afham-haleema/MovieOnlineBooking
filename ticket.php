<?php
include 'header.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name        = $_POST['name'];
    $email       = $_POST['email'];
    $phone       = $_POST['phone'];
    $seat_ids    = $_POST['seat_ids'];
    $showtime_id = $_POST['showtime_id'];
    $total_price = $_POST['total_price'];

    $seat_array     = explode(',', $seat_ids);
    $total_tickets  = count($seat_array);

    // Fetch movie details
    $sql = "SELECT movies.title, showtimes.show_date, showtimes.show_time 
            FROM showtimes 
            JOIN movies ON showtimes.movie_id = movies.movie_id
            WHERE showtimes.showtime_id = :showtime_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['showtime_id' => $showtime_id]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    $date_time = $movie['show_date'] . ' ' . $movie['show_time'];

    // Insert booking details
    $insert_sql = "INSERT INTO users (name, email, phone_number, movie, date_time, tickets, seats)
                   VALUES (:name, :email, :phone, :movie, :date_time, :tickets, :seats)";
    
    $stmt = $pdo->prepare($insert_sql);
    $stmt->execute([
        'name'      => $name,
        'email'     => $email,
        'phone'     => $phone,
        'movie'     => $movie['title'],
        'date_time' => $date_time,
        'tickets'   => $total_tickets,
        'seats'     => $seat_ids
    ]);

    // Get last inserted ID
    $booking_id = $pdo->lastInsertId();

    // Retrieve stored booking
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :booking_id");
    $stmt->execute(['booking_id' => $booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<div class="body">
<div class="cardWrap">
    <p><strong>Successful - </strong>Thank you for booking with us!</p>
    <div class="card cardLeft">
        <h1 class="ticketTitle">Affa <span>Cinema</span></h1>
        <div class="title">
            <h2><?= htmlspecialchars($booking['movie']) ?></h2>
            <span>Movie</span>
        </div>
        <div class="name"> 
            <h2><?= htmlspecialchars($booking['name']) ?></h2>
            <span>Name</span>
        </div>
        <div class="date-time">
            <h2><?= htmlspecialchars($booking['date_time']) ?></h2>
            <span>Date & Time</span>
        </div>
    </div>

    <div class="card cardRight">
        <div class="number">
            <h3><?= htmlspecialchars($booking['seats']) ?></h3>
            <span>Seats</span>
        </div>
    </div>
</div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
