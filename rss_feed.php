<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection settings, replace with your own details
$host = 'localhost:3307';
$user = 'root';
$password = '';
$dbname = 'DSA_Group_3A02';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
}

// Fetch data from MySQL
$sql = "SELECT * FROM poi";
$result = $conn->query($sql);

// Begin RSS feed
header("Content-Type: application/rss+xml; charset=UTF-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0">
<channel>
    <title>Group 03-A2 RSS Feed</title>
    <link>http://localhost/DSA_group_3A2/Index.php</link>
    <description>A summary of all the points of interest in our database</description>
    <language>en-gb</language>
    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    // Loop through each row in the MySQL result and generate RSS items
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            ?>
            <item>
                <title><?php echo htmlspecialchars($row["name"]); ?></title>
                <link><?php echo htmlspecialchars($row["website"]); ?></link>
                <description><?php echo htmlspecialchars($row["description"]); ?></description>
                <pubDate><?php echo date("D, d M Y H:i:s O", strtotime($row["date_column"])); ?></pubDate>
            </item>
            <?php
        }
    }
    ?>
</channel>
</rss>
