$(document).ready(function () {

    let globalPermitId = '';

    let permitTable = $('#permit_table').DataTable({
        "pagingType": "simple",
        "aaSorting": [],
        "stateSave": true,
        "order": [[ 2, "asc" ]]
    }).on('click', 'td.mmp-details-control', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let permitId = splitId[1];
        let reportedOperator = $('#reported_operator_' + permitId).val();
        let tr = $(this).closest('tr');
        let row = permitTable.row( tr );

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
                let permitBody = '<div class="row"><div class="col-md-6">' +
                    '<h3>Location & Contact</h3>' +
                    '<div class="containers">' +
                    '<label for="permit_number_'+ permitId+'">Permit Number: </label>' +
                    '<span id="permit_number_'+ permitId+'"></span><br>' +
                    '<label for="County/Parish_'+ permitId+'">County State: </label>' +
                    '<span id="CountyParish_'+ permitId+'"></span><br>' +
                    '<label for="Township_'+ permitId+'">Township: </label>' +
                    '<span id="Township_'+ permitId+'"></span><br>' +
                    '<label for="OperatorAlias_'+ permitId+'">Operator: </label>' +
                    '<span id="OperatorAlias_'+ permitId+'"></span><br>' +
                    '<label for="area_acres_'+ permitId+'">Acreage: </label>' +
                    '<span id="area_acres_'+ permitId+'"></span><br>' +
                    '<label for="Range_'+ permitId+'">Range: </label>' +
                    '<span id="Range_'+ permitId+'"></span><br>' +
                    '<label for="Section_'+ permitId+'">Section: </label>' +
                    '<span id="Section_'+ permitId+'"></span><br>' +
                    '<label for="District_'+ permitId+'">District: </label>' +
                    '<span id="District_'+ permitId+'"></span><br>' +
                    '<label for="Block_'+ permitId+'">Block: </label>' +
                    '<span id="Block_'+ permitId+'"></span>' +
                    '</div></div>' +
                    '<div class="col-md-6">' +
                    '<h3>Permit Info.</h3>' +
                    '<div class="containers">' +
                    '<label for="permitStatus_'+ permitId+'">Permit Status:</label>' +
                    '<span id="permitStatus_'+ permitId+'"></span><br>' +
                    '<label for="DrillType_'+ permitId+'">Drill Type: </label>' +
                    '<span id="DrillType_'+ permitId+'"></span><br>' +
                    '<label for="PermitType_'+ permitId+'">Permit Type: </label>' +
                    '<span id="PermitType_'+ permitId+'"></span><br>' +
                    '<label for="WellType_'+ permitId+'">Well Type: </label>' +
                    '<span id="WellType_'+ permitId+'"></span><br>' +
                    '<label for="approved_date_'+ permitId+'">Approved Date: </label>' +
                    '<span id="ApprovedDate_'+ permitId+'"></span><br>' +
                    '<label for="submitted_date_'+ permitId+'">Submitted Date: </label>' +
                    '<span id="SubmittedDate_'+ permitId+'"></span><br>' +
                    '<label for="Survey_'+ permitId+'">Survey: </label>' +
                    '<span id="Survey_'+ permitId+'"></span><br>' +
                    '<label for="Abstract_'+ permitId+'">Abstract: </label>' +
                    '<span id="Abstract_'+ permitId+'"></span><br>' +
                    '</div></div></div><br>' +
                    '<div class="row"><div class="col-md-4">' +
                    '<div class="map" id="map_'+ permitId+'"></div></div>' +
                    '<div class="col-md-6"><div style="margin-top:1.5%;">' +
                    '<label style="font-size:20px; font-weight:bold;" for="previous_notes">Previous Landtrac Notes</label>' +
                    '<div class="previous_notes" name="previous_notes" id="previous_notes_'+ permitId+'" contenteditable="false"></div><br>' +
                    '<label style="font-size:20px; font-weight:bold;" for="notes_'+ permitId+'">Submit Landtrac Notes</label><br>' +
                    '<textarea rows="5" class="notes" name="notes" id="notes_'+ permitId+'" style="width:300px;" placeholder="Enter Notes: "></textarea><br>' +
                    '<button type="button" id="update_notes_btn_'+ permitId+'" class="btn btn-primary update_permit_notes_btn">Update Notes</button>' +
                    '</div></div></div>';

                getNotes(id, permitId);
                if ( row.child.isShown() ) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child( permitBody ).show();
                    tr.addClass('shown');

                    let geoPoints = data['permit'][0].btm_geometry.replace(/\s/g, '').replace(/},/g, '},dd').replace('(', '').replace(')', '').split(',dd');
                    let obj = [];
                    let map;
                    let bounds;


                    for (let j in geoPoints) {
                        if (j == 0) {
                            map = new google.maps.Map(document.getElementById('map_'+ permitId), {
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
                }

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

                let township = '';
                if (data['permit'][0]['submitted_date'] !== null) {
                    township = data['permit'][0]['township'];
                } else {
                    township = 'N/A';
                }

                let acreage = '';
                if (data['permit'][0]['acreage'] !== null) {
                    acreage = data['permit'][0]['acreage'];
                } else {
                    acreage = 'N/A';
                }

                let drillType = '';
                if (data['permit'][0]['drill_type'] !== null) {
                    drillType = data['permit'][0]['drill_type'];
                } else {
                    drillType = 'N/A';
                }

                let leaseName = '';
                if (data['permit'][0]['lease_name'] !== null) {
                    leaseName = data['permit'][0]['lease_name'];
                } else {
                    leaseName = 'N/A';
                }

                let range = '';
                if (data['permit'][0]['range'] !== null) {
                    range = data['permit'][0]['range'];
                } else {
                    range = 'N/A';
                }

                let section = '';
                if (data['permit'][0]['section'] !== null) {
                    section = data['permit'][0]['section'];
                } else {
                    section = 'N/A';
                }

                let wellType = '';
                if (data['permit'][0]['well_type'] !== null) {
                    wellType = data['permit'][0]['well_type'];
                } else {
                    wellType = 'N/A';
                }

                $('#Abstract_'+ permitId).text(' ' + abstract);
                $('#ApprovedDate_'+ permitId).text(' ' + approvedDate[0]);
                $('#SubmittedDate_'+ permitId).text(' ' + submittedDate[0]);
                $('#Block_'+ permitId).text(' ' + block);
                $('#permitStatus_'+ permitId).text(' ' + permitStatus);
                $('#CountyParish_'+ permitId).text(' ' + data['permit'][0]['county_parish'] + ', ' + data['permit'][0]['state']);
                $('#DrillType_'+ permitId).text(' ' + drillType);
                $('#LeaseName_'+ permitId).text(' ' + leaseName);
                $('#OperatorAlias_'+ permitId).text(' ' + data['permit'][0]['operator_alias']);
                $('#PermitID_'+ permitId).text(' ' + data['permit'][0]['permit_id']);
                $('#PermitType_'+ permitId).text(' ' + data['permit'][0]['permit_type']);
                $('#permit_number_'+ permitId).text(' ' + data['permit'][0]['permit_number']);
                $('#Range_'+ permitId).text(' ' + range);
                $('#Section_'+ permitId).text(' ' + section);
                $('#Survey_'+ permitId).text(' ' + survey);
                $('#Township_'+ permitId).text(' ' + township);
                $('#WellType_'+ permitId).text(' ' + wellType);
                $('#area_acres_'+ permitId).text(' ' + acreage);
                $('#District_'+ permitId).text(' ' + district);


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
    }).on('click', '.update_permit_notes_btn', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let permitId = splitId[3];
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
                permitId: permitId,
                notes: $('#notes_' + permitId).val(),

            },
            success: function success(data) {
                let updatedNotes = '';

                $.each(data, function (key, value) {
                    updatedNotes += '<span>'+value.notes+'</span>';
                });
                updatedNotes = $('<span>' + updatedNotes + '</span>');

                $('#previous_notes_' + permitId).empty().append(updatedNotes.html());
                $('#notes_' + permitId).val('').text('');
            },
            error: function error(data) {
                $('#notes_' + permitId).val('Note Submission Error. Make sure You Selected a Permit').text('Note Submission Error. Make sure You Selected a Permit');
            }
        });
    }).on('mouseover', '.permit_note', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let noteId = splitId[1];
        let permitId = splitId[2];

        $('#' + id).css('background-color', 'lightgrey');
        $('#delete_permit_note_'+noteId+'_'+permitId).css('display', 'inherit');
    }).on('mouseleave', '.permit_note', function() {
        $('.delete_permit_note').css('display', 'none');
        $('.permit_note').css('background-color', '#F2EDD7FF');
    }).on('click', '.delete_permit_note', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let noteId = splitId[3];
        let permitId = splitId[4];
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

                    $('#previous_notes_' + permitId).empty().append(updatedNotes.html());
                },
                error: function error(data) {
                    console.log(data);
                }
            });
        }
    });

    function getNotes( id, permitId ) {
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

                    $('#previous_notes_' + permitId).empty().append(updatedNotes.html());
                } else {
                    $('#previous_notes_' + permitId).empty();
                }
            },
            error: function error(data) {
                console.log(data);
                $('.notes').val('Note Submission Error. Make sure You Selected a Permit').text('Note Submission Error. Make sure You Selected a Permit');
            }
        });
    }
});