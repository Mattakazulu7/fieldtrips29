
function initMap() {
    // The location of the starting point
    var startPoint = {lat: 52.374, lng: 4.890}; // Coordinates for Beursplein 1, Amsterdam
    var map = new google.maps.Map(document.getElementById('googleMap'), {
        zoom: 15,
        center: startPoint,
    });

    // Add markers for each location
    var markers = [
        {position: startPoint, title: 'Beursplein 1, Meeting Point'},
        {position: {lat: 52.3727598, lng: 4.8936041}, title: 'Red Light District'},
        {position: {lat: 52.3722013, lng: 4.8982086}, title: 'Chinatown'},
        // Add more markers as needed
    ];

    markers.forEach(function(markerInfo) {
        var marker = new google.maps.Marker({
            position: markerInfo.position,
            map: map,
            title: markerInfo.title,
        });
    });
}


