function addAsset() {
    let assetName = document.getElementById('assetName').value;
    let assetDescription = document.getElementById('assetDescription').value; // New field for description
    let assetOwner = document.getElementById('assetOwner').value; // New field for owner

    if (!assetName || !assetOwner) return alert("Please enter a valid asset name and owner.");

    // Handle click on map to add asset
    let clickHandler = function (e) {
        // Create the marker at the clicked location
        let marker = L.marker(e.latlng).addTo(map).bindPopup(`${assetName}<br>Latitude: ${e.latlng.lat}<br>Longitude: ${e.latlng.lng}`);

        // Send asset data to the server to save in the database
        fetch('save-asset.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: assetName,
                description: assetDescription,  // Send description to server
                owner: assetOwner,  // Send owner to server
                latitude: e.latlng.lat,
                longitude: e.latlng.lng
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Asset saved successfully:', data);
                assetMarkers.push(marker);  // Add the marker to the array
                alert('Asset added successfully!');
            } else {
                alert('Failed to save asset. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error saving asset:', error);
            alert('Error saving asset. Please try again.');
        });

        // Remove click handler after the marker is added
        map.off('click', clickHandler);
    };

    // Add a click event listener to the map to place the marker
    map.on('click', clickHandler);
}
