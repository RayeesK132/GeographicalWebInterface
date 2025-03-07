<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role']; // Get the user's role (admin or user)

// Include the database connection
require 'db.php';  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map with Navigation from Current Location</title>

    <!-- Leaflet & Routing Libraries -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Your existing styles */
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
        }
        #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: 0;
        }
        .search-container {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            z-index: 1002;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
            border: 2px solid #ffcc00;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        #searchBar {
            width: 80%;
            padding: 12px 20px;
            font-size: 18px;
            border-radius: 30px;
            border: none;
            outline: none;
            background: #f1f1f1;
            color: #333;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .icon-controls {
            display: flex;
            gap: 10px;
        }
        .icon-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .icon-btn:hover {
            background-color: rgba(0, 0, 0, 0.2);
        }
        .icon-btn i {
            width: 12px;
            height: 24px;
            color: white;
        }
        .asset-container {
            position: absolute;
            top: 1%;
            left: 14%;
            transform: translateX(-50%);
            width: 300px;
            z-index: 1002;
            padding: 15px;
            border-radius: 10px;
            background: white;
            color: black;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }
        .asset-container input, .asset-container button {
            width: 100%;
            margin-top: 10px;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .asset-container button {
            background-color: #ffcc00;
            border: none;
            cursor: pointer;
        }

        /* Back to Dashboard Button */
        .back-to-dashboard {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1003;
            background-color: #ffcc00;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 18px;
            font-weight: 600;
            color: #333;
            text-align: center;
            cursor: pointer;
        }
        .back-to-dashboard:hover {
            background-color: #0b0b0a;
        }
    </style>
</head>
<body>

    <!-- Search & Filter Controls -->
    <div class="search-container">
        <input type="text" id="searchBar" placeholder="Search for a location..." onkeypress="if(event.key === 'Enter') navigateToSearchLocation()">
        <div class="icon-controls">
            <div class="icon-btn" onclick="fetchAndDisplayAssets('school')" title="Schools">
                <i data-lucide="school"></i>
            </div>
            <div class="icon-btn" onclick="fetchAndDisplayAssets('restaurant')" title="Restaurants">
                <i data-lucide="utensils"></i>
            </div>
            <div class="icon-btn" onclick="fetchAndDisplayAssets('park')" title="Parks">
                <i data-lucide="tree-pine"></i>
            </div>
            <div class="icon-btn" onclick="fetchAndDisplayAssets('hospital')" title="Hospitals">
                <i data-lucide="hospital"></i>
            </div>
            <div class="icon-btn" onclick="clearAllMarkersAndNavigation()" title="Clear All">
                <i data-lucide="map-pin"></i>
            </div>
        </div>
    </div>

    <div class="asset-container">
        <input type="text" id="assetName" placeholder="Enter asset name">
        <button onclick="addAsset()">Add Asset</button>
        <button onclick="removeAsset()">Remove Asset</button>
    </div>

    <!-- Map Container -->
    <div id="map"></div>

    <!-- Leaflet Libraries -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <script>
        lucide.createIcons();

        // Initialize map
        let map = L.map('map').setView([53.795, -1.759], 13);
        let baseLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let markersLayer = L.layerGroup().addTo(map);
        let routeControl;
        let userPosition = null;

        // Fetch and display all assets after page load
        function fetchAssets() {
            fetch('map.php?fetchAssets=true')
                .then(response => response.json())
                .then(data => {
                    if (data && data.assets) {
                        data.assets.forEach(asset => {
                            let marker = L.marker([asset.latitude, asset.longitude])
                                .addTo(map)
                                .bindPopup(`${asset.name}<br>Latitude: ${asset.latitude}<br>Longitude: ${asset.longitude}`);
                        });
                    }
                })
                .catch(error => console.error('Error fetching assets:', error));
        }

        // Call fetchAssets when the page loads
        window.onload = fetchAssets;

        // Function to fetch and display assets by type
        function fetchAndDisplayAssets(type) {
            markersLayer.clearLayers();
            let bounds = map.getBounds();
            let query = `https://nominatim.openstreetmap.org/search?format=json&bounded=1&viewbox=${bounds.getWest()},${bounds.getSouth()},${bounds.getEast()},${bounds.getNorth()}&q=${type}`;
            fetch(query)
                .then(response => response.json())
                .then(data => {
                    data.forEach(asset => {
                        let marker = L.marker([asset.lat, asset.lon])
                            .addTo(markersLayer)
                            .bindPopup(`<strong>${asset.display_name}</strong><br>Type: ${type}`);
                    });
                })
                .catch(error => console.log('Error fetching assets:', error));
        }

        // Function to navigate from user's current location to the searched location
        function navigateToSearchLocation() {
            let destination = document.getElementById('searchBar').value;
            if (!destination) {
                alert("Please enter a location to navigate to.");
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    userPosition = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    L.marker([userPosition.lat, userPosition.lng])
                        .addTo(map)
                        .bindPopup("You are here")
                        .openPopup();

                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${destination}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length === 0) {
                                alert("Destination not found.");
                                return;
                            }

                            let destLatLng = [data[0].lat, data[0].lon];
                            if (routeControl) {
                                map.removeControl(routeControl);
                            }

                            routeControl = L.Routing.control({
                                waypoints: [
                                    L.latLng(userPosition.lat, userPosition.lng),
                                    L.latLng(destLatLng[0], destLatLng[1])
                                ],
                                routeWhileDragging: true
                            }).addTo(map);
                        })
                        .catch(error => console.log('Error fetching destination location:', error));
                },
                (error) => {
                    alert("Unable to access your current location. Please enable location services.");
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        // Clear all markers and remove navigation
        function clearAllMarkersAndNavigation() {
            markersLayer.clearLayers();

            if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
            }
        }

        let assetMarkers = [];
        let assetData = [];

        // Function to add asset
        function addAsset() {
            let assetName = document.getElementById('assetName').value;
            if (!assetName) return alert("Enter a valid asset name.");
            
            let clickHandler = function (e) {
                // Create the marker at the clicked location
                let marker = L.marker(e.latlng).addTo(map).bindPopup(`${assetName}<br>Latitude: ${e.latlng.lat}<br>Longitude: ${e.latlng.lng}`);
                
                // Store the marker and its data (name, lat, lng)
                assetMarkers.push(marker);
                assetData.push({
                    name: assetName,
                    lat: e.latlng.lat,
                    lng: e.latlng.lng
                });

                // Send asset data to the server
                fetch('map.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name: assetName,
                        latitude: e.latlng.lat,
                        longitude: e.latlng.lng
                    })
                }).then(response => response.json())
                  .then(data => {
                    if (data.success) {
                        console.log('Asset added and logged successfully.');
                    } else {
                        console.error('Error adding asset:', data.error);
                    }
                }).catch(error => console.error('Error:', error));

                // Double-click event to remove the marker
                marker.on('dblclick', function () {
                    map.removeLayer(marker);
                    assetMarkers = assetMarkers.filter(m => m !== marker);
                    assetData = assetData.filter(data => data.lat !== e.latlng.lat || data.lng !== e.latlng.lng);
                });

                // Remove click handler after the marker is added
                map.off('click', clickHandler);
            };

            // Add a click event listener to the map to place the marker
            map.on('click', clickHandler);
        }

        function removeAsset() {
            if (assetMarkers.length > 0) {
                // Remove the last marker
                let marker = assetMarkers.pop();
                map.removeLayer(marker);

                // Remove the corresponding asset data
                assetData.pop();
            } else {
                alert("No asset to remove.");
            }
        }
    </script>

    <!-- Back to Dashboard Button -->
    <div class="back-to-dashboard">
        <a href="<?php echo $role == 'admin' ? 'admin-dashboard.php' : 'dashboard.php'; ?>" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>
