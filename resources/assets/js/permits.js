$(document).ready(function () {

    let globalPermitId = '';

    $('#permit_table').DataTable({
        "pagingType": "simple",
        "aaSorting": [],
        "stateSave": true,
        "order": [[ 2, "asc" ]]
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

                let district = data['permit'][0]['district'];
                if (data['permit'][0]['district'] === null) {
                    district = 'N/A';
                } else {
                    district = data['permit'][0]['district'];
                }

                let block = data['permit'][0]['block'];
                if (data['permit'][0]['block'] === null) {
                    block = 'N/A';
                } else {
                    block = data['permit'][0]['block'];
                }

                let approvedDate = '';
                if (data['permit'][0]['approved_date'] !== null) {
                    approvedDate = data['permit'][0]['approved_date'].split('T');
                } else {
                    approvedDate[0] = 'N/A';
                }

                let permitStatus = '';
                if (data['permit'][0]['permit_status'] !== null) {
                    permitStatus = data['permit'][0]['permit_status'].split('T');
                } else {
                    permitStatus[0] = 'N/A';
                }

                let submittedDate = '';
                if (data['permit'][0]['submitted_date'] !== null) {
                    submittedDate = data['permit'][0]['submitted_date'].split('T');
                } else {
                    submittedDate[0] = 'N/A';
                }

                $('#Abstract').text(abstract);
                $('#ApprovedDate').text(approvedDate[0]);
                $('#SubmittedDate').text(submittedDate[0]);
                $('#Block').text(block);
                $('#permitStatus').text(permitStatus);
                $('#CountyParish').text(data['permit'][0]['county_parish'] + ', ' + data['permit'][0]['state']);
                $('#DrillType').text(data['permit'][0]['drill_type']);
                $('#LeaseName').text(data['permit'][0]['lease_name']);
                $('#OperatorAlias').text(data['permit'][0]['operator_alias']);
                $('#PermitID').text(data['permit'][0]['permit_id']);
                $('#PermitType').text(data['permit'][0]['permit_type']);
                $('#permit_number').text(data['permit'][0]['permit_number']);
                $('#Range').text(data['permit'][0]['range']);
                $('#Section').text(data['permit'][0]['section']);
                $('#Survey').text(survey);
                $('#Township').text(data['permit'][0]['township']);
                $('#WellType').text(data['permit'][0]['well_type']);
                $('#expiration_primary_term').text('');
                $('#area_acres').text(data['permit'][0]['area_acres']);
                $('#District').text(district);

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
            data: {
                permitId: permitId
            },
            success: function success(data) {
                console.log(data);
                if (data !== undefined && data !== '') {
                    let updatedNotes = '';

                    $.each(data, function (key, value) {
                        updatedNotes += '<span>'+value.notes+'</span>';
                    });
                    updatedNotes = $('<span>' + updatedNotes + '</span>');

                    $('.previous_notes').empty().append(updatedNotes.html());
                } else {
                    $('.previous_notes').empty();
                }
            },
            error: function error(data) {
                console.log(data);
                $('.notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
    });

    $('.update_permit_notes_btn').on('click', function() {
        console.log(globalPermitId);
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
                notes: $('.notes').val(),

            },
            success: function success(data) {
                let updatedNotes = '';

                $.each(data, function (key, value) {
                    updatedNotes += '<span>'+value.notes+'</span>';
                });
                updatedNotes = $('<span>' + updatedNotes + '</span>');

                $('.previous_notes').empty().append(updatedNotes.html());
                $('.notes').val('').text('');
            },
            error: function error(data) {
                $('.notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
    });

    $('.previous_notes').on('mouseover', '.permit_note', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let permitId = splitId[1];
        console.log(permitId);

        $('#' + id).css('background-color', 'lightgrey');
        $('#delete_permit_note_'+permitId).css('display', 'inherit');
    }).on('mouseleave', '.permit_note', function() {
        $('.delete_permit_note').css('display', 'none');
        $('.permit_note').css('background-color', '#F2EDD7FF');
    }).on('click', '.delete_permit_note', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let noteId = splitId[3];
        let response = confirm('Are you sure you want to delete this note?');

        if (response) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                beforeSend: function beforeSend(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
                },
                type: "POST",
                url: '/new-permits/delete/delete-note',
                data: {
                    id: noteId
                },
                success: function success(data) {
                    console.log(data);
                    let updatedNotes = '';

                    $.each(data, function (key, value) {
                        updatedNotes += '<span>'+value.notes+'</span>';
                    });
                    updatedNotes = $('<span>' + updatedNotes + '</span>');

                    $('.previous_notes').empty().append(updatedNotes.html());
                },
                error: function error(data) {
                    console.log(data);
                }
            });
        }
    });
});