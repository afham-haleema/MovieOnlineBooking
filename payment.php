<?php
include 'header.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seat_ids = $_POST['seat_ids'];
    $showtime_id = $_POST['showtime_id'];
    $total_price = $_POST['total_price'];

    $seat_array = explode(',', $seat_ids);
    $seat_list = "'" . implode("','", $seat_array) . "'";
    $total_tickets = count($seat_array);

    $sql = "SELECT movies.title, showtimes.show_date, showtimes.show_time 
            FROM showtimes 
            JOIN movies ON showtimes.movie_id = movies.movie_id
            WHERE showtimes.showtime_id = $showtime_id";
    $result=$conn->query($sql);
    $movie = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="info-container">
    <h2>Payment Details</h2>
    <form action="ticket.php" method="POST">
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
        <p><strong>Total Price:</strong> BHD <?= $total_price ?></p>
    </div>

    <!-- PAYMENT -->
    <div class="section">
        <h3>PAYMENT</h3>
        <label>Card Number:</label> 
        <input type="text" name="card_number" required>

        <label>Expiry Date:</label> 
        <input type="text" name="expiry_date" placeholder="MM/YY" required>

        <label>CVV:</label> 
        <input type="text" name="cvv" required>
    </div>

    <!-- HIDDEN INPUTS -->
    <input type="hidden" name="seat_ids" value="<?= htmlspecialchars($seat_ids) ?>">
    <input type="hidden" name="showtime_id" value="<?= htmlspecialchars($showtime_id) ?>">
    <input type="hidden" name="total_price" value="<?= $total_price ?>">

    <button type="submit" class="pay">Pay Now</button>
    
</form>

</div>
<?php
include 'footer.php';
?>
</body>
</html>
