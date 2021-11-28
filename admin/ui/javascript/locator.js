$(function () {
    if (navigator.geolocation) {
        geoLoc = navigator.geolocation;
        watchID = geoLoc.watchPosition(saveLatLong, null, {
            enableHighAccuracy: true,
            maximumAge: 0
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
});

function saveLatLong(position) {
    $.post('index.php?path=shopper/order/saveLatLong&token=<?= $token ?>',
            {
                "latitude": position.coords.latitude,
                "longitude": position.coords.longitude
            });
}
