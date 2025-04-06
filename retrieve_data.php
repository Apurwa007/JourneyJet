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

// Initialize variables
$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Prepare SQL query
$sql = "SELECT id, departure_city, destination_city, travel_date, bus_number, fare FROM buses WHERE 1=1";

if ($from) {
    $sql .= " AND departure_city LIKE '%" . $conn->real_escape_string($from) . "%'";
}

if ($to) {
    $sql .= " AND destination_city LIKE '%" . $conn->real_escape_string($to) . "%'";
}

if ($date) {
    $sql .= " AND travel_date = '" . $conn->real_escape_string($date) . "'";
}

$result = $conn->query($sql);

$buses = [];

if ($result->num_rows > 0) {
    // Store data in an array
    while ($row = $result->fetch_assoc()) {
        $buses[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Buses</title>
    <link rel="stylesheet" href="retrieve_data_styles.css">
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
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropbtn">ACCOUNT <i class="fa fa-caret-down"></i></a>
                        <div class="dropdown-content">
                            <a href="#">Cancel Ticket</a>
                            <a href="#">Change Travel Date</a>
                            <a href="#">Show My Ticket</a>
                            <a href="sign_up_in.html">Sign Up/Sign In</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    
    <section class="bus-list">
        <div class="container">
            <h1>Available Buses</h1>
            <?php if (!empty($buses)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Date</th>
                            <th>Bus Number</th>
                            <th>Fare</th>
                            <th>Seats</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($buses as $bus): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($bus['departure_city']); ?></td>
                                <td><?php echo htmlspecialchars($bus['destination_city']); ?></td>
                                <td><?php echo htmlspecialchars($bus['travel_date']); ?></td>
                                <td><?php echo htmlspecialchars($bus['bus_number']); ?></td>
                                <td><?php echo htmlspecialchars($bus['fare']); ?></td>
                                <td><button onclick="showSeats(<?php echo $bus['id']; ?>)">Show Seats</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No buses available.</p>
            <?php endif; ?>
        </div>
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
    
    <script>
        function showSeats(busId) {
            // Redirect to the seats page
            window.location.href = "show_seats.php?bus_id=" + busId;
        }
    </script>
</body>
</html>
