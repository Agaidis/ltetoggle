$(document).ready(function () {

    let globalPermitId = '';

    $('#permit_table').DataTable({
        "pagingType": "simple",
        "aaSorting": []
    }).on('click', '.view_permit', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let permitId = splitId[1];
        let reportedOperator = splitId[2];
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            beforeSend: function beforeSend(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            type: "GET",
            url: '/new-permits/getPermitDetails',
            data: {
                permitId: permitId,
                reportedOperator: reportedOperator
            },
            success: function success(data) {
                console.log(data);
                let survey = data['permit'][0]['survey'];
                if (data['permit'][0]['survey'] === null) {
                    survey = 'N/A';
                } else {
                    survey = data['permit'][0]['survey'];
                }

                let abstract = data['permit'][0]['abstract'];
                if (data['permit'][0]['abstract'] === null) {
                    abstract = 'N/A';
                } else {
                    abstract = data['permit'][0]['abstract'];
                }

                let block = data['permit'][0]['block'];
                if (data['permit'][0]['block'] === null) {
                    block = 'N/A';
                } else {
                    block = data['permit'][0]['block'];
                }
                let approvedDate = data['permit'][0]['approved_date'].split('T');

                $('#Abstract').text(abstract);
                $('#ApprovedDate').text(approvedDate[0]);
                $('#Block').text(block);
                $('#CountyParish').text(data['permit'][0]['county_parish'] + ', ' + data['permit'][0]['state']);
                $('#DrillType').text(data['permit'][0]['drill_type']);
                $('#LeaseName').text(data['permit'][0]['lease_name']);
                $('#OperatorAlias').text(data['permit'][0]['operator_alias']);
                $('#PermitID').text(data['permit'][0]['permit_id']);
                $('#PermitType').text(data['permit'][0]['permit_type']);
                $('#Range').text(data['permit'][0]['range']);
                $('#Section').text(data['permit'][0]['section']);
                $('#Survey').text(survey);
                $('#Township').text(data['permit'][0]['township']);
                $('#WellType').text(data['permit'][0]['well_type']);
                $('#expiration_primary_term').text('');
                $('#area_acres').text(data['permit'][0]['area_acres']);

                let geoPoints = data['permit'][0].btm_geometry.replace(/\s/g, '').replace(/},/g, '},dd').replace('(', '').replace(')', '').split(',dd');
                let obj = [];
                let map;
                let bounds;


                for (let j in geoPoints) {
                    if (j == 0) {
                        map = new google.maps.Map(document.getElementById('map'), {
                            center: JSON.parse(geoPoints[j]),
                            zoom: 13,
                            mapTypeId: google.maps.MapTypeId.HYBRID
                        });

                    }
                    obj.push(JSON.parse(geoPoints[j]));
                }

                let locationInfowindow = new google.maps.InfoWindow({
                    content: 'What info do we want in here.',
                });

                let marker = new google.maps.Marker({
                    position: JSON.parse(geoPoints),
                    map: map,
                    infowindow: locationInfowindow
                });

                google.maps.event.addListener(marker, 'click', function() {
                    this.infowindow.open(map, this);
                });

                function ResizeMap() {
                    google.maps.event.trigger(map, "resize");
                }

                $("#VehicleMovementModal").on('shown', function () {
                    ResizeMap();
                });

                bounds = new google.maps.LatLngBounds();
                google.maps.event.addListenerOnce(map, 'tilesloaded', function (evt) {

                    bounds = map.getBounds();
                });

                let input = /** @type {!HTMLInputElement} */(
                    document.getElementById('pac-input'));
                let types = document.getElementById('type-selector');
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

                let polygon = new google.maps.Polygon({
                    path: obj,
                    geodesic: true,
                    strokeColor: '#091096',
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    fillColor: '#B1AAA9',
                    fillOpacity: 0.35,
                });
                polygon.setMap(map);
            },
            error: function error(data) {
                console.log(data);
            }
        });
    }).on('click', '.permit_row', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let permitId = splitId[2];
        globalPermitId = permitId;

        $('.permit_row').css('background-color', 'white');
        $('#' + id).css('background-color', '#e3e3d1');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            beforeSend: function beforeSend(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            type: "GET",
            url: '/new-permits/getNotes',
            dataType: 'json',
            data: {
                permitId: permitId
            },
            success: function success(data) {
                $('.notes').val(data.responseText).text(data.responseText);
            },
            error: function error(data) {
                $('.notes').val(data.responseText).text(data.responseText);
            }
        });
    }).on('change', '.assignee', function() {
        let assignee = $(this)[0].value;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            beforeSend: function beforeSend(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            type: "PUT",
            url: '/new-permits/updateAssignee',
            data: {
                permitId: globalPermitId,
                assigneeId: assignee
            },
            success: function success(data) {
                console.log(data);
            },
            error: function error(data) {
                console.log(data);
            }
        });
    });

    $('.update_permit_notes_btn').on('click', function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            beforeSend: function beforeSend(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            type: "PUT",
            url: '/new-permits/updateNotes',
            data: {
                permitId: globalPermitId,
                notes: $('.notes').val()
            },
            success: function success(data) {
                console.log(data);
            },
            error: function error(data) {
                console.log(data);
            }
        });
    });
});