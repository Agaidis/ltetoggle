$(document).ready(function () {
    let globalLeaseId = '';
    let globalOwnerId = '';

    $('[data-toggle="tooltip"]').tooltip();

    $('#lease_table').DataTable({
        "pagingType": "simple",
        "aaSorting": [],
        "order": [[ 3, "desc" ]]
    }).on('click', '.lease_row', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let leaseId = splitId[2];
        globalLeaseId = leaseId;

        $('.lease_row').css('background-color', 'white');
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
            url: '/mm-platform/getNotes',
            dataType: 'json',
            data: {
                leaseId: leaseId
            },
            success: function success(data) {
                $('.notes').val(data.responseText);
                $('.notes').text(data.responseText)

            },
            error: function error(data) {
                $('.notes').val(data.responseText);
                $('.notes').text(data.responseText)
                console.log(data);
            }
        });
    }).on('click', '.view_lease', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let leaseId = splitId[1];

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
                url: '/mm-platform/getLeaseDetails',
                dataType: 'json',
                data: {
                    leaseId: leaseId
                },
                success: function success(data) {
                    console.log(data);
                    $('#grantor_address').text(data[1][0].grantor_address);
                    $('#grantor').text(data[1][0].grantor);
                    $('#grantee').text(data[1][0].grantee);
                    $('#grantee_alias').text(data[1][0].grantee_alias);
                    $('#exp_primary_term').text(data[1][0].expiration_primary_term);
                    $('#county').text(data[1][0].county_parish);
                    $('#area_acres').text(data[1][0].area_acres);


                    let geoPoints = data[1][0].geometry.replace(/\s/g, '').replace(/},/g, '},dd').replace('(', '').replace(')', '').split(',dd');
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


                    $.each (data[0], function(key, value) {
                        let locationInfowindow = new google.maps.InfoWindow({
                            content: '<label>Lease Name: </label><p>'+value.lease_name+'</p>' +
                            '<label>Abstract: </label><p>'+value.abstract+'</p>' +
                            '<label>Approved Date: </label><p>'+value.approved_date+'</p>'+
                            '<label>Drill Type: </label><p>'+value.drill_type+'</p>'+
                            '<label>Well Type: </label><p>'+value.well_type+'</p>'+
                            '<label>Permit Type: </label><p>'+value.permit_type+'</p>'+
                            '<label>Operator Alias: </label><p>'+value.operator_alias+'</p>',
                        });

                        let marker = new google.maps.Marker({
                            position: JSON.parse(value.btm_geometry),
                            map: map,
                            infowindow: locationInfowindow

                        });

                        google.maps.event.addListener(marker, 'click', function() {
                            this.infowindow.open(map, this);
                        });
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
            });
    }).on('change', '.assignee', function() {
        let id = $(this)[0].id;
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
            url: '/mm-platform/updateAssignee',
            data: {
                leaseId: globalLeaseId,
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

    $('#owner_table').DataTable({
        "pagingType": "simple",
        "aaSorting": [],
        "order": [[ 3, "desc" ]]
    }).on('change', '.owner_assignee', function() {
            let id = $(this)[0].id;
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
                url: '/owner/updateAssignee',
                data: {
                    ownerId: globalOwnerId,
                    assigneeId: assignee
                },
                success: function success(data) {
                    console.log(data);
                },
                error: function error(data) {
                    console.log(data);
                }
            });
        }).on('click', '.owner_row', function () {
            let id = $(this)[0].id;
            let splitId = id.split('_');
            let ownerId = splitId[2];
            globalOwnerId = ownerId;
        }).on('click', '.update_phone_numbers', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[3];

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
            url: '/owner/updatePhoneNumbers',
            data: {
                ownerId: ownerId,
                cell: $('#cell_' + ownerId).val(),
                work: $('#work_' + ownerId).val(),
                home: $('#home_' + ownerId).val()
            },
            success: function success(data) {
                console.log(data);
            },
            error: function error(data) {
                console.log(data);
            }
        });
    }).on('click', '.view_owner', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[1];

        $.ajax({
            beforeSend: function beforeSend(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
            },
            type: "GET",
            url: '/owner',
            data: {
                id: ownerId
            },
            success: function success(data) {
                console.log(data);
                let nameAddressString = data.owner + '<br>' + data.owner_address + '<br>' + data.owner_city + ',' + data.owner_zip;
                $('#owner_name').text(data.owner);
                $('#name_address').append(nameAddressString);
                $('#lease_name').text(data.lease_name);
                $('#lease_description').text(data.lease_description);
                $('#rrc_lease_number').text(data.rrc_lease_number);
                $('#decimal_interest').text(data.owner_decimal_interest);
                $('#interest_type').text(data.owner_interest_type);
                $('#tax_value').text(data.tax_value);
                $('#first_prod').text(data.first_prod_date);
                $('#last_prod').text(data.last_prod_date);
                $('#cum_prod_oil').text(data.cum_prod_oil);
                $('#active_well_count').text(data.active_well_count);
                },
            error: function error(data) {
                console.log(data);
            }
        });
    });

    $('.update_lease_notes_btn').on('click', function () {
        console.log(globalLeaseId);
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
            url: '/mm-platform/updateNotes',
            data: {
                leaseId: globalLeaseId,
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

    $('#wellbore_table').DataTable({
        "pagingType": "simple",
        "aaSorting": []
    });
});