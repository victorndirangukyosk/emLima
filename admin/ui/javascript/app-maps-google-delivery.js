var markerA, markerB;
function initMap(pointA, pointB) {

    console.log("apps");


    var parsed;

    /*try {

        console.log($('#single_delivery_map_ui').val());
        parsed = JSON.parse($('#single_delivery_map_ui').val());
    } catch (e) {
        console.log(e);
        // Oh well, but whatever...
        console.log('no selected theme');
    }*/

    if (pointA) {
        pointA = pointA.split(',');
        pointB = pointB.split(',');
        pointA = new google.maps.LatLng(pointA[0], pointA[1]),
            pointB = new google.maps.LatLng(pointB[0], pointB[1]);
        //pointC = new google.maps.LatLng(pointB[0],pointB[1]);

        //console.log(pointB[1] + 0.34523);
        //console.log(pointB);
        var myOptions = {
            zoom: 7,
            center: pointA,
            //styles: parsed
        },
            map = new google.maps.Map(document.getElementById('map'), myOptions),
            // Instantiate a directions service.
            directionsService = new google.maps.DirectionsService,
            directionsDisplay = new google.maps.DirectionsRenderer({
                map: map
            }),
            markerA = new google.maps.Marker({
                position: pointA,
                /* title: "point A",
                 label: "A",*/
                //map: map
            }),
            markerB = new google.maps.Marker({
                position: pointB,
                /*title: "point B",
                label: "B",*/
                //map: map
            });

        // get route from A to B
        calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB);

        //console.log(myOptions);


        // google.maps.event.trigger(map, 'resize');

        // // Recenter the map now that it's been redrawn               
        // var reCenter = new google.maps.LatLng(50.8505851,4.3680522);
        // map.setCenter(reCenter);

        /*var bounds = new google.maps.LatLngBounds();

        bounds.extend(pointA);
        map.fitBounds(bounds);*/


        //map.setCenter(markerA.getPosition());

        var service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix(
            {
                origins: [pointA],
                destinations: [pointB],
                travelMode: 'DRIVING',
                //unitSystem: UnitSystem,
                avoidHighways: false,
                avoidTolls: true,
            }, callback);
    }

    //alert(pointA);
    /*map.setCenter(new google.maps.LatLng(50.8505851,4.3680522));
    google.maps.event.trigger(map,'resize');*/
}

function initMaps(pointA, pointB, driverDetails) {

    console.log("apps");


    var parsed;

    /*try {

        console.log($('#single_delivery_map_ui').val());
        parsed = JSON.parse($('#single_delivery_map_ui').val());
    } catch (e) {
        console.log(e);
        // Oh well, but whatever...
        console.log('no selected theme');
    }*/

    if (pointA) {
        pointA = pointA.split(',');
        pointB = pointB.split(',');
        pointA = new google.maps.LatLng(pointA[0], pointA[1]),
            pointB = new google.maps.LatLng(pointB[0], pointB[1]);
        //pointC = new google.maps.LatLng(pointB[0],pointB[1]);

        //console.log(pointB[1] + 0.34523);
        //console.log(pointB);
        var myOptions = {
            zoom: 7,
            center: pointA,
            //styles: parsed
        },
            map = new google.maps.Map(document.getElementById('drivermap'), myOptions),
            // Instantiate a directions service.
            directionsService = new google.maps.DirectionsService,
            directionsDisplay = new google.maps.DirectionsRenderer({
                map: map
            }),
            markerA = new google.maps.Marker({
                position: pointA,
                title: driverDetails.driver_name,
                label: "A",
                //map: map
            }),
            markerB = new google.maps.Marker({
                position: pointB,
                /*title: "point B",
                label: "B",*/
                //map: map
            });

        // get route from A to B
        calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB);

        //console.log(myOptions);


        // google.maps.event.trigger(map, 'resize');

        // // Recenter the map now that it's been redrawn               
        // var reCenter = new google.maps.LatLng(50.8505851,4.3680522);
        // map.setCenter(reCenter);

        /*var bounds = new google.maps.LatLngBounds();

        bounds.extend(pointA);
        map.fitBounds(bounds);*/


        //map.setCenter(markerA.getPosition());

        var service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix(
            {
                origins: [pointA],
                destinations: [pointB],
                travelMode: 'DRIVING',
                //unitSystem: UnitSystem,
                avoidHighways: false,
                avoidTolls: true,
            }, callback);
    }

    //alert(pointA);
    /*map.setCenter(new google.maps.LatLng(50.8505851,4.3680522));
    google.maps.event.trigger(map,'resize');*/
}

function callback(response, status) {
    /*console.log("callback");
    console.log(response);*/

    if (status == 'OK') {
        var origins = response.originAddresses;
        var destinations = response.destinationAddresses;

        for (var i = 0; i < origins.length; i++) {
            var results = response.rows[i].elements;
            for (var j = 0; j < results.length; j++) {
                var element = results[j];
                var distance = element.distance.text;
                var duration = element.duration.text;
                var from = origins[i];
                var to = destinations[j];
            }
        }

        console.log(distance);
        console.log(duration);
        console.log('duration');
        //$("#distance").html(distance); $("#duration").html(duration);
    }
}

function calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB) {
    directionsService.route({
        origin: pointA,
        destination: pointB,
        avoidTolls: true,
        avoidHighways: false,
        travelMode: google.maps.TravelMode.DRIVING
    }, function (response, status) {

        console.log(response);
        if (status == google.maps.DirectionsStatus.OK) {

            directionsDisplay.setDirections(response);

            //google.maps.event.trigger(MapInstance,'resize')

            console.log(directionsDisplay);
            console.log(response);
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}

//initMap();
