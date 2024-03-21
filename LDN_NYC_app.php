<!DOCTYPE html>
<html>
<head>
    <title>London and New York City</title>
    <link rel="stylesheet" href="LDN_NYC_app.css">
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

<script src="https://js.arcgis.com/4.29/"></script>
<script>
    require([
        "esri/Map",
        "esri/views/MapView",
        "esri/widgets/BasemapToggle"
    ], (Map, MapView, BasemapToggle) => {
        // Create the Map with an initial basemap for London
        const londonMap = new Map({
            basemap: "topo-vector"
        });

        // Create the MapView and reference the Map in the instance for London
        const londonView = new MapView({
            container: "viewDivLondon",
            map: londonMap,
            center: [0.1276, 51.5072],
            zoom: 10
        });

        // 1 - Create the BasemapToggle widget for London
        const toggleLondon = new BasemapToggle({
            view: londonView,
            nextBasemap: "hybrid"
        });

        // Add the BasemapToggle widget to the view for London
        londonView.ui.add(toggleLondon, "top-right");

        // Create the Map with an initial basemap for New York
        const newYorkMap = new Map({
            basemap: "topo-vector"
        });

        // Create the MapView and reference the Map in the instance for New York
        const newYorkView = new MapView({
            container: "viewDivNewYork",
            map: newYorkMap,
            zoom: 12,
            center: [-73.935242, 40.73] // longitude, latitude
        });
    });
</script>

<script>
    window.weatherWidgetConfig = window.weatherWidgetConfig || [];

    // Weather widget configuration for London
    window.weatherWidgetConfig.push({
        selector: ".weatherWidgetLondon",
        apiKey: "84AAH7ENBA8DZMWGPEVWR67MT", // Sign up for your personal key
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
        apiKey: "84AAH7ENBA8DZMWGPEVWR67MT", // Same API key as for London
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
</script>
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
    'poi_cat' => 'SELECT * FROM poi_cat'
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
?>
</body>
</html>
