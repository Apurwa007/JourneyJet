<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "journeyjet";
$port = "3308";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$bus_id = isset($_GET['bus_id']) ? $_GET['bus_id'] : '';

if (!$bus_id) {
    die("Bus ID is required");
}

// Get the bus number and fare per seat for the bus
$bus_result = $conn->query("SELECT bus_number, fare FROM buses WHERE id = " . $conn->real_escape_string($bus_id));
if ($bus_result->num_rows > 0) {
    $bus_row = $bus_result->fetch_assoc();
    $bus_number = $bus_row['bus_number'];
    $fare_per_seat = $bus_row['fare'];
} else {
    die("Bus information not available for the selected bus.");
}

// Prepare SQL query
$sql = "SELECT seat_number, status FROM seats WHERE bus_id = " . $conn->real_escape_string($bus_id);
$result = $conn->query($sql);

$seats = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['fare'] = $fare_per_seat;  // Add fare to each seat record
        $seats[] = $row;
    }
} else {
    echo "No seats available.";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Seats</title>
    <link rel="stylesheet" href="show_seatstyles.css">
</head>
<body>
    <header>
        <div class="navbar">
            <img src="logo.png" alt="JourneyJet Logo" class="logo" style="height: 100px;">
            <nav>
                <ul>
                    <li><a href="index.html">HOME</a></li>
                    <li><a href="about.html">ABOUT</a></li>
                    <li><a href="services.html">SERVICES</a></li>
                    <li><a href="contact.html">CONTACT</a></li>
                    <li><a href="sign_up_in.html">Sign Up/Sign In</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <section class="seats-container">
        <h1>Available Seats for Bus <?php echo htmlspecialchars($bus_number); ?></h1>
        <div class="bus-layout">
            <?php 
            $seatMap = [];
            foreach ($seats as $seat) {
                $seatMap[$seat['seat_number']] = $seat;
            }

            // Display seats in the specified layout (4 rows with different columns as per the image)
            $rows = [4, 4, 5, 5]; // Define the number of seats in each row
            $seatCounter = 1;

            foreach ($rows as $seatsInRow) {
                for ($i = 0; $i < $seatsInRow; $i++) {
                    $seatStatus = isset($seatMap[$seatCounter]) ? $seatMap[$seatCounter]['status'] : 'available';
                    $seatFare = isset($seatMap[$seatCounter]) ? $seatMap[$seatCounter]['fare'] : $fare_per_seat; // Use bus fare if seat fare not set
                    $seatClass = $seatStatus === 'booked' ? 'seat occupied' : 'seat';
                    echo "<div class='$seatClass' data-seat-number='$seatCounter' data-seat-fare='$seatFare'></div>";
                    $seatCounter++;
                }
                echo "<div class='clear'></div>"; // Add a clear div to break the row
            }
            ?>
        </div>
        <div id="total-fare" class="total-fare">Total Fare: 0</div>
        <button id="continue-button" class="continue-button" disabled>Continue</button>
    </section>

    <section class="about-section">
        <div class="containers">
            <h2>About Us</h2>
            <p>JourneyJet is dedicated to simplifying seat reservations in buses, ensuring a comfortable and secure journey for all passengers.</p>
        </div>
        <div class="container">
            <h2>Our Services</h2>
            <p>Seat Reservation</p>
            <p>E-Ticketing</p>
            <p>Live GPS Tracking</p>
            <p>Customer Support</p>
        </div>
        <div class="container">
            <h2>Contact</h2>
            <p>+91 xxxxxxxxxx</p>
        </div>
        <div class="container">
            <h2>Email Address</h2>
            <p>JourneyJet@gmail.com</p>
        </div>
    </section>
    <footer>
        <p>Copyright © 2024 JourneyJet</p>
    </footer>
    <script src="seat_selection.js"></script>
</body>
</html>
