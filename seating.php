<?php
include 'db.php';

$movie_id = $_GET['movie_id'];

$stmt = $pdo->prepare("SELECT showtime_id, show_date, show_time FROM showtimes WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$showtimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Booking</title>
    <link rel="stylesheet" href="/styles.css">
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

        <!-- Seat rows -->
        <?php
        $rows = [
            ['A1','A2','A3','A4','A5','A6','A7','A8','A9'],
            ['B1','B2','B3','B4','B5','B6','B7','B8','B9'],
            ['C1','C2','C3','C4','C5','C6','C7']
        ];

        foreach ($rows as $row) {
            echo "<div class='row'>";
            foreach ($row as $seat) {
                echo "<div class='seat' data-seat-id='$seat'></div>";
            }
            echo "</div>";
        }
        ?>
    </div>

    <p class="text">
        You have selected <span id="count">0</span> seats for a price of BHD <span id="total">0</span>
    </p>

    <input type="hidden" id="selected_seat_ids" name="seat_ids" value="">
    <input type="hidden" id="selected_showtime_id" name="showtime_id" value="">
    <input type="hidden" id="total_price" name="total_price" value="">

    <div class="showtime-buttons">
        <?php foreach ($showtimes as $showtime): ?>
            <button type="button" class="showtime-btn" data-showtime-id="<?= htmlspecialchars($showtime['showtime_id']) ?>">
                <?= htmlspecialchars($showtime['show_date']) ?> <?= htmlspecialchars($showtime['show_time']) ?>
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
const ticketPrice = 4;

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

bookButton.addEventListener('click', () => {
    bookingForm.submit();
});
</script>

</body>
</html>
