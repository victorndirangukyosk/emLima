<!DOCTYPE html>
<html>

    <head lang="en">
        <meta charset="UTF-8">
        <!-- Bootstrap stuff -->
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyCiMBCIxWmuh1TVf4u6xJzYZS_xhFe04so&sensor=false&libraries=places'></script>
        <script src="<?= $base?>front/ui/theme/mvgv2/maps/locationpicker.jquery.min.js"></script>
        <title>Simple example</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .pac-container {
                z-index: 99999;
            }
        </style>
    </head>

    <body>
        <button data-target="#us6-dialog" data-toggle="modal">Click hear to open dialog</button>
        <div id="us6-dialog" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-horizontal" style="width: 550px">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Location:</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="us3-address" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Radius:</label>

                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="us3-radius" />
                                </div>
                            </div>
                            <div id="us3" style="width: 100%; height: 400px;"></div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="m-t-small">
                                <label class="p-r-small col-sm-1 control-label">Lat.:</label>

                                <div class="col-sm-3">
                                    <input type="text" class="form-control" style="width: 110px" id="us3-lat" />
                                </div>
                                <label class="p-r-small col-sm-2 control-label">Long.:</label>

                                <div class="col-sm-3">
                                    <input type="text" class="form-control" style="width: 110px" id="us3-lon" />
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <script>
                                function updateControls(addressComponents) {
                                    console.log(addressComponents.addressLine1);
                                    console.log(addressComponents.city);
                                    console.log(addressComponents.stateOrProvince);
                                    console.log(addressComponents.postalCode);
                                    console.log(addressComponents.country);
                                }
                                $('#us3').locationpicker({
                                    location: {
                                        latitude: -1.2799559,
                                        longitude: 36.7702275
                                    },
                                    radius: 300,
                                    onchanged: function (currentLocation, radius, isMarkerDropped) {
                                        console.log(currentLocation);
                                        console.log(radius);
                                        console.log(isMarkerDropped);
                                        var addressComponents = $(this).locationpicker('map').location.addressComponents;
                                        updateControls(addressComponents);
                                    },
                                    oninitialized: function (component) {
                                        var addressComponents = $(component).locationpicker('map').location.addressComponents;
                                        updateControls(addressComponents);
                                    },
                                    inputBinding: {
                                        latitudeInput: $('#us3-lat'),
                                        longitudeInput: $('#us3-lon'),
                                        radiusInput: $('#us3-radius'),
                                        locationNameInput: $('#us3-address')
                                    },
                                    enableAutocomplete: true,
                                    autocompleteOptions: {
                                    componentRestrictions: { country: 'ke' }
                                    },
                                    markerIcon: '<?= $base?>front/ui/theme/mvgv2/maps/marker.png'
                                });
                                $('#us6-dialog').on('shown.bs.modal', function () {
                                    $('#us3').locationpicker('autosize');
                                });
                            </script>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </body>

</html>