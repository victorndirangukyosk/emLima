<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap stuff -->
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <!-- -->

        <!-- Location picker -->
        <script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyCiMBCIxWmuh1TVf4u6xJzYZS_xhFe04so&sensor=false&libraries=places'></script>
        <script src="<?= $base?>front/ui/theme/mvgv2/maps/locationpicker.jquery.js"></script>
        <title>jquery-location-picker demo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <div class="container">
            <div id="examples">
                <p>

                <h3>Providing options</h3>
                <pre>
&lt;div id="somecomponent" style="width: 500px; height: 400px;"&gt;&lt;/div&gt;
&lt;script&gt;
$('#somecomponent').locationpicker({
	location: {latitude: 46.15242437752303, longitude: 2.7470703125},
	radius: 300,
        markerIcon: 'http://www.iconsdb.com/icons/preview/tropical-blue/map-marker-2-xl.png'
});
&lt;/script&gt;
                </pre>
                <p>Result</p>

                <div id="us1" style="width: 500px; height: 400px;"></div>
                <script>
                    $('#us1').locationpicker({
                        location: {
                            latitude: 46.15242437752303,
                            longitude: 2.7470703125
                        },
                        radius: 300,
                        markerIcon: 'http://www.iconsdb.com/icons/preview/tropical-blue/map-marker-2-xl.png'

                    });
                </script>

                <h3>Binding UI with the widget</h3>
                <pre>
Location: &lt;input type="text" id="us2-address" style="width: 200px"/&gt;
Radius: &lt;input type="text" id="us2-radius"/&gt;
&lt;div id="us2" style="width: 500px; height: 400px;">&lt;/div&gt;
Lat.: &lt;input type="text" id="us2-lat"/&gt;
Long.: &lt;input type="text" id="us2-lon"/&gt;
&lt;script>$('#us2').locationpicker({
	location: {latitude: 46.15242437752303, longitude: 2.7470703125},
	radius: 300,
	inputBinding: {
        latitudeInput: $('#us2-lat'),
        longitudeInput: $('#us2-lon'),
        radiusInput: $('#us2-radius'),
        locationNameInput: $('#us2-address')
    }
	});
&lt;/script&gt;
                </pre>
                <p>Result:</p>

                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Location:</label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="us2-address" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Radius:</label>

                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="us2-radius" />
                        </div>
                    </div>
                    <div id="us2" style="width: 550px; height: 400px;"></div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="m-t-small">
                        <label class="p-r-small col-sm-1 control-label">Lat.:</label>

                        <div class="col-sm-1">
                            <input type="text" class="form-control" style="width: 110px" id="us2-lat" />
                        </div>
                        <label class="p-r-small col-sm-1 control-label">Long.:</label>

                        <div class="col-sm-1">
                            <input type="text" class="form-control" style="width: 110px" id="us2-lon" />
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <script>
                    $('#us2').locationpicker({
                        location: {
                            latitude: 46.15242437752303,
                            longitude: 2.7470703125
                        },
                        radius: 300,
                        inputBinding: {
                            latitudeInput: $('#us2-lat'),
                            longitudeInput: $('#us2-lon'),
                            radiusInput: $('#us2-radius'),
                            locationNameInput: $('#us2-address')
                        },
                        enableAutocomplete: true
                    });
                </script>

                <h3>Subscribing for events</h3>

                <p>The following example illustrates how to subscribe "Change" event. See the list of the available events along with functions signature <a href="#events">above</a>.</p>
                <pre>
$('#us3').locationpicker({
location: {latitude: 46.15242437752303, longitude: 2.7470703125},
radius: 300,
inputBinding: {
	latitudeInput: $('#us3-lat'),
	longitudeInput: $('#us3-lon'),
	radiusInput: $('#us3-radius'),
	locationNameInput: $('#us3-address')
},
enableAutocomplete: true,
onchanged: function(currentLocation, radius, isMarkerDropped) {
	alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
}
                </pre>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Location:</label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="us3-address" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">Radius:</label>

                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="us3-radius" />
                        </div>
                    </div>
                    <div id="us3" style="width: 550px; height: 400px;"></div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="m-t-small">
                        <label class="p-r-small col-sm-1 control-label">Lat.:</label>

                        <div class="col-sm-2">
                            <input type="text" class="form-control" style="width: 110px" id="us3-lat" />
                        </div>
                        <label class="p-r-small col-sm-1 control-label">Long.:</label>

                        <div class="col-sm-2">
                            <input type="text" class="form-control" style="width: 110px" id="us3-lon" />
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <script>
                        $('#us3').locationpicker({
                            location: {
                                latitude: 46.15242437752303,
                                longitude: 2.7470703125
                            },
                            radius: 300,
                            inputBinding: {
                                latitudeInput: $('#us3-lat'),
                                longitudeInput: $('#us3-lon'),
                                radiusInput: $('#us3-radius'),
                                locationNameInput: $('#us3-address')
                            },
                            enableAutocomplete: true,
                            onchanged: function (currentLocation, radius, isMarkerDropped) {
                                alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                            }
                        });
                    </script>
                </div>
                <h3>Manipulating map widget from callback</h3>

                <p>If you need direct access to the actual Google Maps widget you can use <code>map</code> method as follows. This example illustrates how to set zoom pragmatically each time when location has been changed.</p>
                <pre>
$('#us4').locationpicker({
location: {latitude: 46.15242437752303, longitude: 2.7470703125},
radius: 300,
onchanged: function(currentLocation, radius, isMarkerDropped) {
    var mapContext = $(this).locationpicker('map');
    mapContext.map.setZoom(20);
}
                </pre>
                <div>
                    <div id="us4" style="width: 500px; height: 400px;"></div>
                    <script>
                        $('#us4').locationpicker({
                            location: {
                                latitude: 46.15242437752303,
                                longitude: 2.7470703125
                            },
                            radius: 300,
                            onchanged: function (currentLocation, radius, isMarkerDropped) {
                                var mapContext = $(this).locationpicker('map');
                                mapContext.map.setZoom(13);
                            }
                        });
                    </script>
                </div>

                <h3>Advanced usage of geo decoder features</h3>

                <p>
                    Along with decoded readable location name plugin returns address split on components (state, postal code, etc.) which in some cases can be pretty useful.
                </p>
                <pre>
function updateControls(addressComponents) {
    $('#us5-street1').val(addressComponents.addressLine1);
    $('#us5-city').val(addressComponents.city);
    $('#us5-state').val(addressComponents.stateOrProvince);
    $('#us5-zip').val(addressComponents.postalCode);
    $('#us5-country').val(addressComponents.country);
}
$('#us5').locationpicker({
    location: {latitude: 42.00, longitude: -73.82480799999996},
    radius: 300,
    onchanged: function (currentLocation, radius, isMarkerDropped) {
        var addressComponents = $(this).locationpicker('map').location.addressComponents;
        updateControls(addressComponents);
    },
    oninitialized: function(component) {
        var addressComponents = $(component).locationpicker('map').location.addressComponents;
        updateControls(addressComponents);
    }
});
                </pre>
                <div>
                    <div class="container-fluid">
                        <div class="col-lg-6">
                            <div id="us5" style="width: 500px; height: 400px;"></div>
                            <p></p>
                        </div>
                        <div class="col-lg-6">
                            <div class="form container-fluid">
                                <div class="row form-group">
                                    <label class="col-sm-2 control-label">Street:</label>

                                    <div class="col-sm-6">
                                        <input class="form-control" id="us5-street1" disabled="disabled">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-sm-2 control-label">City:</label>

                                    <div class="col-sm-6">
                                        <input class="form-control" id="us5-city" disabled="disabled">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-sm-2 control-label">State or Province:</label>

                                    <div class="col-sm-6">
                                        <input class="form-control" id="us5-state" disabled="disabled">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-sm-2 control-label">Postal code:</label>

                                    <div class="col-sm-6">
                                        <input class="form-control" id="us5-zip" disabled="disabled">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-sm-2 control-label">Country:</label>

                                    <div class="col-sm-6">
                                        <input class="form-control" id="us5-country" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <script>
                        function updateControls(addressComponents) {
                            $('#us5-street1').val(addressComponents.addressLine1);
                            $('#us5-city').val(addressComponents.city);
                            $('#us5-state').val(addressComponents.stateOrProvince);
                            $('#us5-zip').val(addressComponents.postalCode);
                            $('#us5-country').val(addressComponents.country);
                        }
                        $('#us5').locationpicker({
                            location: {
                                latitude: 42.00,
                                longitude: -73.82480799999996
                            },
                            radius: 300,
                            onchanged: function (currentLocation, radius, isMarkerDropped) {
                                var addressComponents = $(this).locationpicker('map').location.addressComponents;
                                updateControls(addressComponents);
                            },
                            oninitialized: function (component) {
                                var addressComponents = $(component).locationpicker('map').location.addressComponents;
                                updateControls(addressComponents);
                            }
                        });
                    </script>
                </div>

                <div>
                    <h2 class="page-header" id="credits">Credits</h2> Dmitry Berezovsky, Logicify (<a href="http://logicify.com/" target="_blank">http://logicify.com/</a>)
                </div>

            </div>
            <footer>
                <p class="pull-right"><a href="#start">Back to top</a></p>

                <p><a href="http://logicify.com/" target="_blank">Logicify</a></p>
            </footer>
        </div>
    </body>

</html>
