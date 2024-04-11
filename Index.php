<!DOCTYPE html>
<html>
<head>
    <style>body {
  background-color: #333; /* Dark background color */
}</style>
    <title>London and New York City</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://js.arcgis.com/4.29/esri/themes/light/main.css">
</head>
<body>
<div class="hero-wrapper">
  <header class="hero">
    <div class="hero-sizing"></div>
    <div class="hero-content">
      <h1>City of London <br>&<br>New York City</h1>
    </div>
  </header>
</div>
<div class="container">
    <div class="weatherAndMap">
        <section class="weatherSection">
            <h2>London Weather</h2>
            <div class="weatherWidgetLondon"></div>
        </section>
        <section class="mapSection">
            <h2>London Map</h2>
            <div id="viewDivLondon" class="map"></div>
        </section>
    </div>
    <div class="weatherAndMap">
        <section class="weatherSection">
            <h2>New York City Weather</h2>
            <div class="weatherWidgetNewYork"></div>
        </section>
        <section class="mapSection">
            <h2>New York Map</h2>
            <div id="viewDivNewYork" class="map"></div>
        </section>
    </div>
</div>
<div class="links_to_galleries">
    <h2>Photo Galleries</h2>
    <div class="box">
        <h3>City of London Pictures</h3>
        <p>Follow the link</p>
        <a href="London_gallery.php" class="button">View London</a>
    </div>
    <div class="box">
        <h3>New York City Pictures</h3>
        <p>Follow the link</p>
        <a href="NYC_gallery.php" class="button">View NYC</a>
    </div>
</div>

<script src="https://js.arcgis.com/4.29/"></script>
<script>
    // Function to fetch JSON data from config.json file
    async function fetchConfig() {
        const response = await fetch('config.json');
        return await response.json();
    }

    // Call fetchConfig function to get configuration data
    fetchConfig().then(config => {
        require([
        "esri/Map",
        "esri/views/MapView"
    ], (Map, MapView) => {
        // Function to create map and view with given parameters
        function createMapAndViewById(containerId, centerCoords, basemapType) {
            // Create the Map with an initial basemap
            const map = new Map({
                basemap: basemapType
            });

            // Create the MapView and reference the Map in the instance
            const view = new MapView({
                container: containerId,
                map: map,
                center: centerCoords,
                zoom: 10,
                ui: {
                    components: ["attribution"]
                }
            });

            // Function to stop event propagation and disable zooming
            function stopEvtPropagation(event) {
                event.stopPropagation();
            }

            // Disable mouse wheel scroll zooming on the view
            view.on("mouse-wheel", stopEvtPropagation);

            // Disable zooming via double-click on the view
            view.on("double-click", stopEvtPropagation);

            // Disable zooming out via double-click + Control on the view
            view.on("double-click", ["Control"], stopEvtPropagation);

            // Disable dragging and panning on the view
            view.on("drag", stopEvtPropagation);

            // Disable dragging and panning with Shift key pressed
            view.on("drag", ["Shift"], stopEvtPropagation);

            // Disable dragging and panning with Shift + Control keys pressed
            view.on("drag", ["Shift", "Control"], stopEvtPropagation);

            return view;
        }

                   // Create map and view for London using config data
                   createMapAndViewById(config.london.containerId, config.london.centerCoords, config.london.basemapType);

                    // Create map and view for New York using config data
                    createMapAndViewById(config.newYork.containerId, config.newYork.centerCoords, config.newYork.basemapType);
                    });

    });
</script>





<script>

    // Fetch the configuration JSON file asynchronously
async function fetchConfig() {
    const response = await fetch('config.json');
    return await response.json();
}

// Function to configure weather widgets based on the fetched configuration
async function configureWeatherWidgets() {
    // Fetch the configuration JSON
    const config = await fetchConfig();
    window.weatherWidgetConfig = window.weatherWidgetConfig || [];

    // Weather widget configuration for London
    window.weatherWidgetConfig.push({
        selector: ".weatherWidgetLondon",
        apiKey: config.visualCrossingWeather.apiKey, // Sign up for your personal key
        location: "London, UK", // Enter the address for London
        unitGroup: "metric", // "us" or "metric"
        forecastDays: 5, // how many days forecast to show
        title: "London, UK", // optional title to show
        showTitle: true,
        showConditions: true
    });

    // Weather widget configuration for New York
    window.weatherWidgetConfig.push({
        selector: ".weatherWidgetNewYork",
        apiKey: config.visualCrossingWeather.apiKey, // Same API key as for London
        location: "New York, NY, USA", // Enter the address for New York
        unitGroup: "metric", // "us" or "metric"
        forecastDays: 5, // how many days forecast to show
        title: "New York, NY, USA", // optional title to show
        showTitle: true,
        showConditions: true
    });

    (function () {
        var d = document, s = d.createElement('script');
        s.src = 'https://www.visualcrossing.com/widgets/forecast-simple/weather-forecast-widget-simple.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
}

configureWeatherWidgets();
</script>
<div class='comments'>
    <h2 style="margin-top: 200px;">Post a Comment</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="username" placeholder="Your Name" required>
        <textarea name="comment" placeholder="Your Comment" required></textarea>
        <button type="submit">Post Comment</button>
    </form>

    <h2>Comments</h2>
    <?php
// Load configuration from JSON file
$configFile = 'config.json';
$config = json_decode(file_get_contents($configFile), true);

// Extract database details
$dbHost = $config['database']['host'];
$dbPort = $config['database']['port'];
$dbUsername = $config['database']['username'];
$dbPassword = $config['database']['password'];
$dbName = $config['database']['dbname'];

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

// Check if the comment form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['comment'])) {
    $username = htmlspecialchars($_POST['username']);
    $comment = htmlspecialchars($_POST['comment']);

    // Prepare an SQL statement to insert the submitted comment
    $stmt = $conn->prepare("INSERT INTO comments (username, comment) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $comment);

    // Execute the statement and check for errors
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the statement

    // Redirect the user to the same page to prevent form resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit; // Make sure no other code is executed after the redirect

}

// Fetch all comments from the database
$result = $conn->query("SELECT username, comment, posted_at FROM comments ORDER BY posted_at DESC");

    // Display comments
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<p><strong>" . $row["username"] . "</strong> (" . $row["posted_at"] . ")</p>";
            echo "<p>" . $row["comment"] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }

    $conn->close(); // Close the connection
    ?>
</div>


<div class="rss-feed">
    <h2>RSS Feed: Data About London and New York City</h2>
    
    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
        // URL of the RSS feed
        $rssFeedUrl = 'http://localhost:81/DSAy2/DSA_group_3A2/rss_feed.php';
        
        // Fetch RSS feed
        $rss = simplexml_load_file($rssFeedUrl);
        
        // Check if RSS feed was loaded successfully
        if ($rss) {
            // Loop through each item in the RSS feed and display it
            foreach ($rss->channel->item as $item) {
                $title = $item->title;
                $link = $item->link;
                $description = $item->description;
                $pubDate = $item->pubDate;
                
                // Output HTML for feed item
                echo '<div class="feed-item">';
                echo '<h3><a href="' . $link . '">' . $title . '</a></h3>';
                echo '<p class="pub-date">' . $pubDate . '</p>';
                echo '<p class="description">' . $description . '</p>';
                echo '</div>';
            }
        } else {
            // Display error message if RSS feed couldn't be loaded
            echo '<p>Error loading RSS feed</p>';
        }
    ?>
</div>



<div class="database">
    <h2>Data about London and NYC</h2>
    <?php

    $host = 'localhost:3307';
    $user = 'root';
    $password = '';
    $dbname = 'DSA_Group_3A02';
    $conn = new mysqli($host, $user, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Arrays to hold SQL queries for each table
    $tables = [
        'city' => 'SELECT * FROM city',
        'images' => 'SELECT * FROM images',
        'poi' => 'SELECT * FROM poi',
        'poi_images' => 'SELECT * FROM poi_images',
        'cat' => 'SELECT * FROM cat',
        'poi_cat' => 'SELECT * FROM poi_cat',
        'comments' => 'SELECT * FROM comments'
    ];

    // Loop through each table and display its contents
    foreach ($tables as $tableName => $sql) {
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Display the table name
            echo "<h3>Contents of {$tableName} Table:</h3>";
            echo "<table border='1'>";

            // Fetch the first row to get column names
            $firstRow = $result->fetch_assoc();
            if ($firstRow) {
                // Table header
                echo "<tr>";
                foreach ($firstRow as $columnName => $value) {
                    echo "<th>{$columnName}</th>";
                }
                echo "</tr>";

                // Table body
                // Display the first row
                echo "<tr>";
                foreach ($firstRow as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";

                // Display the rest of the rows
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }
            }
            echo "</table><br>";
        } else {
            echo "<p>No results found in {$tableName}.</p>";
        }
    }

    $conn->close(); // Close the connection
    ?>
</div>
</body>
</html>