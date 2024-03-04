<?php
// Establish a connection to the database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'city';
$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve search query from the form submission
$query = $_POST['query'];

// Construct the SQL query to search for players
$sql = "SELECT p.FirstName, p.LastName, p.Age, p.Position, n.Country
        FROM player p
        INNER JOIN nationality n ON p.NationalityID = n.NationalityID
        WHERE CONCAT(p.FirstName, ' ', p.LastName) LIKE '%$query%'";

// Execute the query and retrieve the results
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    // Display the results in an HTML table
    echo "<h2>Search Results:</h2>";
    echo "<table>";
    echo "<tr><th>First Name</th><th>Last Name</th><th>Age</th><th>Position</th><th>Nationality</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['FirstName'] . "</td>";
        echo "<td>" . $row['LastName'] . "</td>";
        echo "<td>" . $row['Age'] . "</td>";
        echo "<td>" . $row['Position'] . "</td>";
        echo "<td>" . $row['Country'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No results found.</p>";
}

// Close the database connection
mysqli_close($conn);
?>
