<?php
include 'db.php';

$movie_id = $_GET['movie_id'];
$sql = "SELECT showtime_id, show_date, show_time FROM showtimes WHERE movie_id = $movie_id";
$result=$conn->query($sql);

$showtimes = [];
while ($row = $result->fetch_assoc()) {
    $showtimes[] = $row;
}


$conn->close();

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Booking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<form id="bookingForm" action="payment.php" method="POST">
    <ul class="showcase">
        <li><div class="seat"></div><small>Available</small></li>
        <li><div class="seat_selected"></div><small>Selected</small></li>
        <li><div class="booked"></div><small>Booked</small></li>
    </ul>

    <div class="container">
        <div class="screen"></div>
        <h1>Screen</h1>
        
        <div class="row">
            <div class="seat" data-seat-id="A1"></div>
            <div class="seat" data-seat-id="A2"></div>
            <div class="seat" data-seat-id="A3"></div>
            <div class="seat" data-seat-id="A4"></div>
            <div class="seat" data-seat-id="A5"></div>
            <div class="seat" data-seat-id="A6"></div>
            <div class="seat" data-seat-id="A7"></div>
            <div class="seat" data-seat-id="A8"></div>
            <div class="seat" data-seat-id="A9"></div>
        </div>

        <div class="row">
            <div class="seat" data-seat-id="B1"></div>
            <div class="seat" data-seat-id="B2"></div>
            <div class="seat" data-seat-id="B3"></div>
            <div class="seat" data-seat-id="B4"></div>
            <div class="seat" data-seat-id="B5"></div>
            <div class="seat" data-seat-id="B6"></div>
            <div class="seat" data-seat-id="B7"></div>
            <div class="seat" data-seat-id="B8"></div>
            <div class="seat" data-seat-id="B9"></div>
        </div>

        <div class="row">
            <div class="seat" data-seat-id="C1"></div>
            <div class="seat" data-seat-id="C2"></div>
            <div class="seat" data-seat-id="C3"></div>
            <div class="seat" data-seat-id="C4"></div>
            <div class="seat" data-seat-id="C5"></div>
            <div class="seat" data-seat-id="C6"></div>
            <div class="seat" data-seat-id="C7"></div>
        </div>

    </div>

    <p class="text">
        You have selected <span id="count">0</span> seats for a price of BHD <span id="total">0</span>
    </p>

    <input type="hidden" id="selected_seat_ids" name="seat_ids" value="">
    <input type="hidden" id="selected_showtime_id" name="showtime_id" value="">
    <input type="hidden" id="total_price" name="total_price" value="">

    <div class="showtime-buttons">
        <?php foreach ($showtimes as $showtime): ?>
            <button type="button" class="showtime-btn" data-showtime-id="<?= $showtime['showtime_id'] ?>">
                <?= $showtime['show_date'] ?> <?= $showtime['show_time'] ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div class='book-now-container'>
        <button type="button" class='book-now-btn' id="book_button" disabled>Proceed to Payment</button>
    </div>
</form>

<script>
const seats = document.querySelectorAll('.seat');
const selectedSeatIdsInput = document.getElementById('selected_seat_ids');
const bookButton = document.getElementById('book_button');
const countDisplay = document.getElementById('count');
const totalDisplay = document.getElementById('total');
const bookingForm = document.getElementById('bookingForm');

let selectedSeats = [];
let selectedShowtimeId = "";
const ticketPrice = 4; // Price per ticket




seats.forEach(seat => {
    seat.addEventListener('click', () => {
        if (!seat.classList.contains('booked')) {
            seat.classList.toggle('seat_selected');
            const seatId = seat.getAttribute('data-seat-id');
            if (seat.classList.contains('seat_selected')) {
                selectedSeats.push(seatId);
            } else {
                selectedSeats = selectedSeats.filter(id => id !== seatId);
            }
            updateSeatInfo();
        }
    });
});



function updateSeatInfo() {
    selectedSeatIdsInput.value = selectedSeats.join(',');
    countDisplay.textContent = selectedSeats.length;
    totalDisplay.textContent = selectedSeats.length * ticketPrice;
    document.getElementById('total_price').value = selectedSeats.length * ticketPrice;
    bookButton.disabled = selectedSeats.length === 0 || selectedShowtimeId === "";
}

const showtimeButtons = document.querySelectorAll('.showtime-btn');
showtimeButtons.forEach(button => {
    button.addEventListener('click', function() {
        showtimeButtons.forEach(btn => btn.classList.remove('selected'));
        this.classList.add('selected');
        selectedShowtimeId = this.getAttribute('data-showtime-id');
        document.getElementById('selected_showtime_id').value = selectedShowtimeId;
        updateSeatInfo(); 
    });
});

// Navigate to payment page on click
bookButton.addEventListener('click', () => {
    bookingForm.submit();
});
</script>

</body>
</html>
