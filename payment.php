<?php
include 'header.php';
include 'db.php';

$movie = null;
$seat_array = [];
$total_tickets = 0;
$seat_ids = '';
$showtime_id = '';
$total_price = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seat_ids = $_POST['seat_ids'] ?? '';
    $showtime_id = $_POST['showtime_id'] ?? '';
    $total_price = $_POST['total_price'] ?? '';

    $seat_array = explode(',', $seat_ids);
    $seat_list = "'" . implode("','", array_map('trim', $seat_array)) . "'";
    $total_tickets = count($seat_array);

    try {
        $stmt = $pdo->prepare("SELECT movies.title, showtimes.show_date, showtimes.show_time 
                                FROM showtimes 
                                JOIN movies ON showtimes.movie_id = movies.movie_id
                                WHERE showtimes.showtime_id = :showtime_id");
        $stmt->execute(['showtime_id' => $showtime_id]);
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching movie details: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>

<div class="info-container">
    <h2>Payment Details</h2>

    <?php if ($movie): ?>
    <form action="ticket.php" method="POST">
        <!-- USER DETAILS -->
        <div class="section">
            <h3>YOUR DETAILS</h3>
            <label>Full Name:</label> 
            <input type="text" name="name" required>

            <label>Email:</label> 
            <input type="email" name="email" required>

            <label>Phone Number:</label> 
            <input type="text" name="phone" required>
        </div>

        <!-- MOVIE INFO -->
        <div class="section">
            <h3>MOVIE INFO</h3>
            <p><strong>Movie:</strong> <?= htmlspecialchars($movie['title']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($movie['show_date']) ?></p>
            <p><strong>Time:</strong> <?= htmlspecialchars($movie['show_time']) ?></p>
            <p><strong>Tickets:</strong> <?= $total_tickets ?> Seat(s)</p>
            <p><strong>Seats:</strong> <?= htmlspecialchars(implode(', ', $seat_array)) ?></p>
            <p><strong>Total Price:</strong> BHD <?= htmlspecialchars($total_price) ?></p>
        </div>

        <!-- PAYMENT INFO -->
        <div class="section">
            <h3>PAYMENT</h3>
            <label>Card Number:</label> 
            <input type="text" name="card_number" required>

            <label>Expiry Date:</label> 
            <input type="text" name="expiry_date" placeholder="MM/YY" required>

            <label>CVV:</label> 
            <input type="text" name="cvv" required>
        </div>

        <!-- HIDDEN FIELDS -->
        <input type="hidden" name="seat_ids" value="<?= htmlspecialchars($seat_ids) ?>">
        <input type="hidden" name="showtime_id" value="<?= htmlspecialchars($showtime_id) ?>">
        <input type="hidden" name="total_price" value="<?= htmlspecialchars($total_price) ?>">

        <button type="submit" class="pay">Pay Now</button>
    </form>
    <?php else: ?>
        <p>Error loading movie details. Please go back and try again.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
