<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Location on OSM with Routing</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/fontAwesome.css">
    <link rel="stylesheet" href="css/light-box.css">
    <link rel="stylesheet" href="css/templatemo-style.css">
    
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
        .destination-marker {
            color: red;
        }
        .poi-marker {
            color: blue;
        }
        .start-marker, .end-marker {
            background-color: yellow;
            border: 2px solid red;
            border-radius: 50%;
            width: 12px;
            height: 12px;
        }
        #poiTable {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        #poiTable th, #poiTable td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        #poiTable th {
            background-color: #f2f2f2;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            color: white;
            padding: 10px;
        }
        nav .logo a {
            color: white;
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
        }
        nav .menu-icon {
            cursor: pointer;
        }
        nav .menu-icon span {
            display: block;
            width: 25px;
            height: 3px;
            background: white;
            margin: 5px 0;
        }
    </style>
</head>
<body>

    <!-- Navigation Menu -->
    <nav>
        <div class="logo">
            <a href="#">EMNS<em>Safety</em></a>
        </div>
        <div class="menu-icon">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <br><br><br><br><br><br>

    <!-- Main content for the map and routing -->
    <h1>Location</h1>
    <div id="map"></div>
    <button id="getRouteButton" style="margin-top: 10px;">Get Route</button>
    <div style="margin-top: 10px;">
        <input type="text" id="destinationInput" placeholder="Enter destination name" />
        <button id="setDestinationButton">Set Destination</button>
    </div>
    
    <h2>Emergency Places</h2>
    <table id="poiTable">
        <thead>
            <tr>
                <th>Place Name</th>
                <th>Type</th>
                <th>Distance from Start (m)</th>
            </tr>
        </thead>
        <tbody id="poiTableBody"></tbody>
    </table>


    <br>
        <div class="learn-more text-center">
            <button onclick="window.location.href='index.html'" class="btn btn-secondary" id="learn-more-btn">Go to Home Page</button>
        </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script>
        var map = L.map('map').setView([0, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var routingControl;
        var userMarker;
        var destinationMarker;
        var selectedDestination;

        var startMarkerIcon = L.divIcon({className: 'start-marker'});
        var endMarkerIcon = L.divIcon({className: 'end-marker'});

        function onLocationFound(e) {
            var radius = e.accuracy / 2;
            if (userMarker) {
                map.removeLayer(userMarker);
            }
            userMarker = L.marker(e.latlng, {icon: startMarkerIcon}).addTo(map)
                .bindPopup("You are here!").openPopup();
            L.circle(e.latlng, radius).addTo(map);
            map.setView(e.latlng, 13);
        }

        function onLocationError(e) {
            alert(e.message);
        }

        map.locate({setView: true, maxZoom: 13});
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);

        function plotRoute(destination) {
            if (routingControl) {
                map.removeControl(routingControl);
            }
            routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(userMarker.getLatLng().lat, userMarker.getLatLng().lng),
                    L.latLng(destination.lat, destination.lng)
                ],
                createMarker: function(i, wp, nWps) {
                    if (i === 0) {
                        return L.marker(wp.latLng, {icon: startMarkerIcon}).bindPopup("Start: Your Location");
                    } else if (i === nWps - 1) {
                        return L.marker(wp.latLng, {icon: endMarkerIcon}).bindPopup("End: Destination");
                    }
                },
                routeWhileDragging: true
            }).addTo(map);
            loadNearbyPlaces(destination);
        }

        function loadNearbyPlaces(destination) {
            if (map._poiMarkers) {
                map._poiMarkers.forEach(marker => {
                    map.removeLayer(marker);
                });
            }
            var poiTableBody = document.getElementById('poiTableBody');
            poiTableBody.innerHTML = '';
            var query = `
                [out:json];
                (
                    node["amenity"="hospital"](around:2000, ${destination.lat}, ${destination.lng});
                    node["amenity"="garage"](around:2000, ${destination.lat}, ${destination.lng});
                    node["amenity"="fuel"](around:2000, ${destination.lat}, ${destination.lng});
                );
                out body;
            `;
            fetch(`https://overpass-api.de/api/interpreter?data=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    map._poiMarkers = [];
                    data.elements.forEach(element => {
                        if (element.lat && element.lon) {
                            var poiMarker = L.marker([element.lat, element.lon], {
                                icon: L.divIcon({className: 'poi-marker'})
                            }).addTo(map)
                            .bindPopup(element.tags.name || "Unnamed");

                            map._poiMarkers.push(poiMarker);
                            var distance = map.latLngToLayerPoint(userMarker.getLatLng()).distanceTo(
                                map.latLngToLayerPoint([element.lat, element.lon])
                            );
                            var row = poiTableBody.insertRow();
                            row.insertCell(0).textContent = element.tags.name || "Unnamed Place";
                            row.insertCell(1).textContent = element.tags.amenity || "Unknown Type";
                            row.insertCell(2).textContent = Math.round(distance) + " m";
                        }
                    });
                })
                .catch(error => {
                    console.error("Error fetching POIs:", error);
                });
        }

        function getCoordinatesFromName(locationName) {
            return fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(locationName)}&format=json&limit=1`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        return { lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) };
                    } else {
                        throw new Error("Location not found");
                    }
                });
        }

        document.getElementById('setDestinationButton').onclick = function() {
            var input = document.getElementById('destinationInput').value;
            if (input) {
                getCoordinatesFromName(input)
                    .then(coordinates => {
                        selectedDestination = coordinates;
                        if (destinationMarker) {
                            map.removeLayer(destinationMarker);
                        }
                        destinationMarker = L.marker([coordinates.lat, coordinates.lng], {
                            icon: endMarkerIcon
                        }).addTo(map)
                        .bindPopup("Destination: " + input).openPopup();
                        plotRoute(selectedDestination);
                    })
                    .catch(error => {
                        alert(error.message);
                    });
            } else {
                alert("Please enter a destination.");
            }
        };

        document.getElementById('getRouteButton').onclick = function() {
            if (selectedDestination) {
                plotRoute(selectedDestination);
            } else {
                alert("Please set a destination first.");
            }
        };
    </script>
</body>
</html>
