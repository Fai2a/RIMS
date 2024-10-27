// Map initialization function
function initMap() {
    // Create a map and set its view to the default center
    const map = L.map('map').setView([30.3753, 69.3451], 8); // Default center (Pakistan) and zoom level

    // Add a tile layer to the map (OpenStreetMap tiles)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Fetching data from server
    fetch('/getMechanicsData')
        .then(response => response.json())
        .then(data => {
            console.log(data)
            data.forEach(mechanic => {
                const marker = L.marker([mechanic.latitude, mechanic.longitude]).addTo(map);
                
                // Optionally, add a popup to the marker
                marker.bindPopup(`<h3>${mechanic.name}</h3><p>${mechanic.address}</p>`)
                      .openPopup();
            });
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Initialize the map when the window loads
window.onload = initMap;
  

const express = require('express');
const app = express();
const port = 3000;

// Mock data; replace with database query
const mechanicsData = [
    { id: 1, name: 'Workshop A', latitude: 30.3753, longitude: 69.3451, address: 'Address A' },
    { id: 2, name: 'Workshop B', latitude: 31.5204, longitude: 74.3587, address: 'Address B' },
    // Add more data as needed
];

app.get('/getMechanicsData', (req, res) => {
    res.json(mechanicsData);
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
