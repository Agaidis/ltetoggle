$(document).ready(function () {
    let globalOwnerId = '';
    let globalOwnerName = '';

    $('.previous_notes').html($('#hidden_permit_notes').val());


    $('.owner_follow_up').datepicker().on('change', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let uniqueId = splitId[3];

        let date = $('#owner_follow_up_' + uniqueId).val();

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
            url: '/mineral-owner/updateFollowUp',
            data: {
                id: uniqueId,
                date: date
            },
            success: function success(data) {
                console.log(data);
            },
            error: function error(data) {
                console.log(data);
            }
        });
    });

    let ownerTable = $('.owner_table').DataTable({
        "pagingType": "simple",
        "pageLength" : 25,
        "aaSorting": [],
        "order": [[ 6, "desc" ]]
    }).on('change', '.owner_assignee', function() {
        let id = $(this)[0].id;
        let assignee = $(this)[0].value;
        let ownerId = id.split('_');

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
            url: '/mineral-owner/updateAssignee',
            data: {
                ownerId: ownerId[1],
                assigneeId: assignee
            },
            success: function success(data) {

                console.log(assignee);
                if (assignee !== "0") {
                    $("#owner_follow_up_" + ownerId[1]).datepicker("setDate", "2");
                }
            },
            error: function error(data) {
                console.log(data);
            }
        });
    }).on('click', '.owner_row', function() {
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
            url: '/mineral-owner/updatePhoneNumbers',
            data: {
                ownerId: ownerId,

            },
            success: function success(data) {
                console.log(data);
            },
            error: function error(data) {
                console.log(data);
            }
        });
    }).on('click', 'td.owner-details-control', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[1];
        globalOwnerId = ownerId;
        let tr = $(this).closest('tr');
        let row = ownerTable.row( tr );

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
            url: '/mineral-owners',
            data: {
                id: ownerId
            },
            success: function success(data) {
                let ownerBody = '<div class="row">' +
                    '<div class="col-md-6">' +
                    '<h3 style="text-align: center;">Lease Info</h3>' +
                    '<div class="containers">' +
                    '<label for="lease_name_display_'+ownerId+'">Lease Name: </label>' +
                    '<span id="lease_name_display_'+ownerId+'"></span><br>' +
                    '<label for="lease_description_'+ownerId+'">Lease Description: </label>' +
                    '<span id="lease_description_'+ownerId+'"></span><br><br>' +
                    '<label for="rrc_lease_number_'+ownerId+'">RRC Lease Number: </label>' +
                    '<span id="rrc_lease_number_'+ownerId+'"></span><br>' +
                    '</div></div>' +
                    '<div class="col-md-6">' +
                    '<h3 style="text-align: center;">Well Production</h3>' +
                    '<div class="containers">' +
                    '<label class="addit_labels" for="active_well_count_'+ownerId+'">Well Count: </label>' +
                    '<span id="active_well_count_'+ownerId+'"></span><br>' +
                    '<label class="addit_labels" for="first_prod_'+ownerId+'">First Prod Date: </label>' +
                    '<span id="first_prod_'+ownerId+'"></span><br>' +
                    '<label class="addit_labels" for="last_prod_'+ownerId+'">Last Prod Date: </label>' +
                    '<span id="last_prod_'+ownerId+'"></span><br>' +
                    '<label class="addit_labels" for="years_of_prod_'+ownerId+'">Years of Production: </label>' +
                    '<span id="years_of_prod_'+ownerId+'"></span><br>' +



                    '<label class="addit_labels" for="cum_prod_oil_'+ownerId+'">Total Oil Production: </label>' +
                    '<span id="cum_prod_oil_'+ownerId+'"></span><br>' +
                    '<label class="addit_labels" for="cum_prod_gas_'+ownerId+'">Total Gas Production: </label>' +
                    '<span id="cum_prod_gas_'+ownerId+'"></span><br>' +
                    '<label class="addit_labels" for="bbls_'+ownerId+'">BBLS (OIL): </label>' +
                    '<span id="bbls_'+ownerId+'"></span><br>' +
                    '<label class="addit_labels" for="gbbls_'+ownerId+'">MNX (GAS): </label>' +
                    '<span id="gbbls_'+ownerId+'"></span><br>' +
                    '' +


                    '</div>' +
                    '</div></div>' +
                    '<div class="row"><div class="col-md-6">' +
                    '<h3 style="text-align: center;">Mineral Interest & Pricing Info.  </h3>' +
                    '<div class="containers">' +
                    '<div class="row">' +
                    '<div class="offset-2 col-md-5">' +
                    '<label class="addit_labels" for="decimal_interest_'+ownerId+'">Decimal Interest: </label>' +
                    '<span id="decimal_interest_'+ownerId+'"></span>' +
                    '</div>' +
                    '<div class="col-md-4">' +
                    '<label class="addit_labels" style="margin-left:-15%;" for="interest_type_'+ownerId+'">Interest Type: </label>' +
                    '<span id="interest_type_'+ownerId+'"></span>' +
                    '</div></div>' +
                    '<div class="form-group form-inline">' +
                    '<label class="addit_labels" for="interest_type_'+ownerId+'">Monthly Revenue: </label>' +
                    '<input type="text" style="margin-left:8.5%;" class="form-control monthly_revenue" id="monthly_revenue_'+ownerId+'" disabled />' +
                    '</div>' +
                    '<div class="form-group form-inline">' +
                    '<label class="addit_labels" for="owner_price_'+ownerId+'">Pricing per NRA: </label>' +
                    '<input type="text" style="margin-left:10%;" class="form-control owner_price" name="owner_price" id="owner_price_'+ownerId+'" placeholder="$" />' +
                    '</div>' +
                    '<div class="form-group form-inline">' +
                    '<label class="addit_labels" for="net_royalty_acres_'+ownerId+'">Net Royalty Acres: </label>' +
                    '<input type="text" style="margin-left:7.5%;" class="form-control net_royalty_acres" disabled id="net_royalty_acres_'+ownerId+'" />' +
                    '</div>' +
                    '<div class="form-group form-inline">' +
                    '<label class="addit_labels" for="total_price_for_interest_'+ownerId+'">Total Price For Interest: </label>' +
                    '<input type="text" style="margin-left:2%;" class="form-control total_price_for_interest" disabled id="total_price_for_interest_'+ownerId+'" />' +
                    '</div>' +
                    '<div class="form-group form-inline">' +
                    '<label class="addit_labels" for="oil_price">Oil Price: </label>' +
                    '<input type="text" style="margin-left:18.5%;" class="form-control oil_price" disabled />' +
                    '</div>' +
                    '<div class="form-group form-inline">' +
                    '<label class="addit_labels" for="gas_price">Gas Price: </label>' +
                    '<input type="text" style="margin-left:17%;" class="form-control gas_price" disabled />' +
                    '</div>' +
                    '<div class="form-group form-inline">' +
                    '<label class="addit_labels" for="bnp_'+ownerId+'">BNP: </label>' +
                    '<input type="text" style="margin-left:22.8%;" class="form-control bnp" disabled id="bnp_'+ownerId+'" />' +
                    '</div>' +
                    '<div class="form-group form-inline">' +
                    '<label class="addit_labels" for="ytp">Years to PayOff: </label>' +
                    '<input type="text" style="margin-left:10%;" class="form-control ytp" id="ytp_'+ownerId+'" disabled />' +
                    '</div>' +
                    '</div></div>' +
                    '<div class="col-md-6">' +
                    '<div style="text-align:center;" class="col-md-12">' +
                    '<label style="font-size:20px; font-weight:bold;" for="notes">Owner Notes</label>' +
                    '<div class="previous_owner_notes" id="previous_owner_notes_'+ownerId+'" name="previous_owner_notes" contenteditable="false"></div>' +
                    '</div>' +
                    '<div style="text-align:center;" class="col-md-12">' +
                    '<label style="font-size:20px; font-weight:bold;" for="owner_notes_'+ownerId+'">Enter Owner Notes</label>' +
                    '<textarea rows="4" class="owner_notes" id="owner_notes_'+ownerId+'" name="notes" style="width:100%;" placeholder="Enter Notes: "></textarea>' +
                    '<div class="col-md-12">' +
                    '<button type="button" id="update_owner_notes_btn_'+ownerId+'" class="btn btn-primary update_owner_notes_btn">Update Notes</button>' +
                    '</div></div></div>';

                if ( row.child.isShown() ) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(ownerBody).show();
                    tr.addClass('shown');
                }

                let price = 0.0;
                if (data.price !== null) {
                    price = data.price;
                }

                $('#lease_name_display_'+ownerId).text(' ' + data.lease_name);
                $('#owner_price_'+ownerId).val(' $' + price);
                $('#lease_description_'+ownerId).text(' ' + data.lease_description);
                $('#rrc_lease_number_'+ownerId).text(' ' + data.rrc_lease_number);
                $('#decimal_interest_'+ownerId).text(' ' + data.owner_decimal_interest);
                $('#interest_type_'+ownerId).text(' ' + data.owner_interest_type);

                let monthlyRevenue = data.tax_value / 12;
                monthlyRevenue = monthlyRevenue.toFixed(2);
                monthlyRevenue = numberWithCommas(monthlyRevenue);
                $('#monthly_revenue_' +ownerId).val(monthlyRevenue);

                $('#active_well_count_' + ownerId).text(' ' + $('#well_count').val());
                $('#first_prod_'+ownerId).text(' ' + $('#first_month').text());
                $('#last_prod_'+ownerId).text(' ' + $('#last_month').text());
                $('#cum_prod_oil_'+ownerId).text(' ' + $('#total_oil').text());
                $('#cum_prod_gas_'+ownerId).text(' ' + $('#total_gas').text());
                $('#years_of_prod_'+ownerId).text(' ' + $('#years_of_prod').text());
                $('#bbls_'+ownerId).text(' ' + $('#bbls').text());
                $('#gbbls_'+ownerId).text(' ' + $('#gbbls').text());

                let ownerPrice = $('#owner_price_'+ownerId).val();
                if (ownerPrice !== undefined) {
                    ownerPrice = ownerPrice.replace('$', '');
                } else {
                    ownerPrice = 0;
                }

                let netRoyaltyAcres = data.owner_decimal_interest / .125 * $('.acreage').val();
                netRoyaltyAcres = netRoyaltyAcres.toFixed(4);
                $('#net_royalty_acres_'+ownerId).val(netRoyaltyAcres);

                let total = ownerPrice * $('#net_royalty_acres_'+ownerId).val();
                let totalPriceForInterest = total.toFixed(2);
                let totalPriceForInterestWithCommas = numberWithCommas(totalPriceForInterest);

                $('#total_price_for_interest_'+ownerId).val( '$' + totalPriceForInterestWithCommas);

                let neededIncome = totalPriceForInterest / data.owner_decimal_interest;
                let bnp = neededIncome / data.oilPrice;
                bnp = bnp.toFixed(2);
                let bnpWithComma = numberWithCommas(bnp);

                $('.oil_price').val(data.oilPrice);
                $('.gas_price').val(data.gasPrice);
                $('#bnp_' + ownerId).val(bnpWithComma);

                let bbls = $('#bbls').text();
                bbls = bbls.replace(',', '');

                let ytp = bnp / bbls;

                ytp = ytp.toFixed(2);
                let ytpWithComma = numberWithCommas(ytp);
                $('#ytp_' + ownerId).val(ytpWithComma);

                getOwnerNotes( ownerId );
            },
            error: function error(data) {
                console.log(data);
            }
        });
    }).on('change', '.wellbore_dropdown', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[2];
        let wellType = $(this)[0].value;

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
            url: '/mineral-owner/updateWellType',
            data: {
                ownerId: ownerId,
                wellType: wellType
            },
            success: function success(data) {
                if (wellType !== "0") {
                    $("#owner_follow_up_" + ownerId).datepicker("setDate", "2");
                }
            },
            error: function error(data) {
                console.log(data);
            }
        });
    }).on('focusout', '.owner_price', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[2];

        let ownerPrice = $('#owner_price_' +ownerId).val();
        ownerPrice = ownerPrice.replace('$', '');

        let total = ownerPrice * $('#net_royalty_acres_' +ownerId).val();
        let totalPriceForInterest = total.toFixed(2);
        let totalPriceForInterestWithComma = numberWithCommas(totalPriceForInterest);

        $('#total_price_for_interest_' +ownerId).val( '$' + totalPriceForInterestWithComma);

        let neededIncome = totalPriceForInterest / $('#decimal_interest_'+ownerId).text();
        let bnp = neededIncome / $('.oil_price').val();
        bnp = bnp.toFixed(2);
        let bnpWithComma = numberWithCommas(bnp);

        $('#bnp_' + ownerId).val(bnpWithComma);

        let bbls = $('#bbls').text();
        bbls = bbls.replace(',', '');

        let ytp = bnp / bbls;

        ytp = ytp.toFixed(2);
        let ytpWithComma = numberWithCommas(ytp);
        $('#ytp_' + ownerId).val(ytpWithComma);

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
            url: '/mineral-owners/update/OwnerPrice',
            data: {
                id: ownerId,
                price: ownerPrice
            },
            success: function success(data) {
                console.log(data);

            },
            error: function error(data) {
                console.log(data);
            }
        });
    }).on('click', '.update_owner_notes_btn', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[4];

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
            url: '/mineral-owner/updateNotes',
            data: {
                ownerId: ownerId,
                leaseName: $('#lease_name').val(),
                notes: $('#owner_notes_' +ownerId).val()
            },
            success: function success(data) {
                $("#owner_follow_up_" + globalOwnerId).datepicker("setDate", "2");
                let updatedNotes = '';

                $.each(data, function (key, value) {
                    updatedNotes += '<span>'+value.notes+'</span>';
                });
                updatedNotes = $('<span>' + updatedNotes + '</span>');

                $('#previous_owner_notes_'+ ownerId).empty().append(updatedNotes.html());
                $('#owner_notes_'+ownerId).val('').text('');

                $('#assignee_' + ownerId).val($('#user_id').val());
            },
            error: function error(data) {
                console.log(data);
                $('#owner_notes_'+ownerId).val('Note Submission Error. Make sure You Selected an Owner').text('Note Submission Error. Make sure You Selected an Owner');
            }
        });
    }).on('mouseover', '.owner_note', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let noteId = splitId[1];
        let ownerId = splitId[2];

        $('#' + id).css('background-color', 'lightgrey');
        $('#delete_owner_note_'+noteId+'_'+ownerId).css('display', 'inherit');
    }).on('mouseleave', '.owner_note', function() {
        $('.delete_owner_note').css('display', 'none');
        $('.owner_note').css('background-color', '#F2EDD7FF');
    }).on('click', '.delete_owner_note', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let noteId = splitId[3];
        let ownerId = splitId[4];
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
                url: '/mineral-owners/delete/delete-note',
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

                    $('#previous_owner_notes_'+ ownerId).empty().append(updatedNotes.html());
                },
                error: function error(data) {
                    console.log(data);
                }
            });
        }
    }).on('click', '.add_phone_btn', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[2];

        $('#new_phone_desc').val('').text('');
        $('#new_phone_number').val('').text('');

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
            url: '/mineral-owners/getOwnerNumbers',
            data: {
                ownerId: ownerId
            },
            success: function success(data) {
                console.log(data);
                let phoneNumbers = '<div>';
                $.each(data, function (key, value) {
                    phoneNumbers += '<span><div id="phone_'+value.id+'" style="padding: 2%;">'+
                        '<span style="font-weight: bold;">'+value.phone_desc+': </span>'+
                        '<span><a href="tel:'+value.id+'">'+value.phone_number+' </a></span>'+
                        '<span style="cursor:pointer; color:red; margin-left:5%;" class="soft_delete_phone fas fa-trash" id="soft_delete_'+value.id+'" "></span>'+
                        '<span style="cursor:pointer; color:darkorange; margin-left:5%;" class="push_back_phone fas fa-hand-point-right" id="push_back_phone_'+value.id+'" "></span>'+
                        '</div></span>';
                });
                phoneNumbers += '</div>';

                $('.phone_container').empty().append($(phoneNumbers).html());
            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
    });

    $('.phone_container').on('click', '.soft_delete_phone', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let uniqueId = splitId[2];

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
            url: '/mineral-owner/softDeletePhone',
            data: {
                id: uniqueId
            },
            success: function success(data) {
                console.log(data);
                $('#phone_'+uniqueId).remove();

            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });

    }).on('click', '.push_back_phone', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let uniqueId = splitId[3];

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
            url: '/mineral-owner/pushPhoneNumber',
            data: {
                id: uniqueId,
                reason: ''
            },
            success: function success(data) {
                console.log(data);
                $('#phone_'+uniqueId).remove();

            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });

    });

    $('.submit_phone_btn').on('click', function() {
        console.log('haha');

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
            url: '/mineral-owner/addPhone',
            data: {
                ownerId: globalOwnerId,
                phoneDesc: $('#new_phone_desc').val(),
                phoneNumber: $('#new_phone_number').val(),
                leaseName: $('#lease_name').val()
            },
            success: function success(data) {

                $('#new_phone_desc').val('').text('');
                $('#new_phone_number').val('').text('');

                let phoneNumber = '<span><div id="phone_'+data.id+'" style="padding: 2%;">'+
                    '<span style="font-weight: bold;">'+data.phone_desc+': </span>'+
                    '<span><a href="tel:'+data.id+'">'+data.phone_number+' </a></span>'+
                    '<span style="cursor:pointer; color:red; margin-left:5%;" class="soft_delete_phone fas fa-trash" id="soft_delete_'+data.id+'" "></span>'+
                    '<span style="cursor:pointer; color:darkorange; margin-left:5%;" class="push_back_phone fas fa-hand-point-right" id="push_back_phone_'+data.id+'" "></span>'+
                    '</div></span>';

            $('.phone_container').append($(phoneNumber).html());

            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
    });

    /*              END PHONE CAPABILITIES              */



    $('.acreage').on('focusout', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let uniqueId = splitId[1];

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
            url: '/mineral-owners/updateAcreage',
            data: {
                id: uniqueId,
                acreage: $('.acreage').val()
            },
            success: function success(data) {
                console.log(data);

            },
            error: function error(data) {
                console.log(data);
            }
        });
    });


    let map;
    let bounds = new google.maps.LatLngBounds();

    if (toggle.allRelatedPermits !== undefined && toggle.allRelatedPermits !== 'undefined' && toggle.allRelatedPermits.length !== 0) {

        $.each(toggle.allRelatedPermits, function (key, value) {
            let surfaceLng = '{"lng":' + value.SurfaceLongitudeWGS84;
            let surfaceLat = '"lat":' + value.SurfaceLatitudeWGS84 + '}';
            let btmGeo = value.btm_geometry.replace(/\s/g, '').replace(/},/g, '},dd').replace('(', '').replace(')', '').split(',dd');
            let position = new google.maps.LatLng(JSON.parse(surfaceLng + ',' + surfaceLat));

            bounds.extend(position);

            let permitMarker = new google.maps.Marker({
                position: position,
                map: map,
                label: 'SF'
            });

            let btmPosition = new google.maps.LatLng(JSON.parse(btmGeo));
            bounds.extend(btmPosition);

            let SurfaceMarker = new google.maps.Marker({
                position: btmPosition,
                map: map,
                label: 'BM'
            });

            let flightPath = new google.maps.Polyline({
                path: [
                    JSON.parse(surfaceLng + ',' + surfaceLat),
                    JSON.parse(btmGeo)
                ],
                geodesic: true,
                strokeColor: "#ab0000",
                strokeOpacity: 1.0,
                strokeWeight: 2
            });

            flightPath.setMap(map);
        });
        let surfaceLng = '{"lng":' + toggle.allRelatedPermits[0].SurfaceLongitudeWGS84;
        let surfaceLat = '"lat":' + toggle.allRelatedPermits[0].SurfaceLatitudeWGS84 + '}';

        map = new google.maps.Map(document.getElementById('proMap'), {
            zoom: 13,
            center: JSON.parse(surfaceLng + ',' + surfaceLat),
            mapTypeId: google.maps.MapTypeId.HYBRID
        });
    } else {
        console.log( toggle.allWells[0]);
        let surfaceLng = '{"lng":' + toggle.allWells[0].SurfaceHoleLongitudeWGS84;
        let surfaceLat = '"lat":' + toggle.allWells[0].SurfaceHoleLatitudeWGS84 + '}';

        map = new google.maps.Map(document.getElementById('proMap'), {
            zoom: 13,
            center: JSON.parse(surfaceLng + ',' + surfaceLat),
            mapTypeId: google.maps.MapTypeId.HYBRID
        });
    }

    // Display multiple markers on a map
    let infoWindow = new google.maps.InfoWindow(), marker;

    // Loop through our array of markers & place each one on the map
    $.each(toggle.allWells, function (key, value) {

        let surfaceLng = '{"lng":' + value.SurfaceHoleLongitudeWGS84;
        let surfaceLat = '"lat":' + value.SurfaceHoleLatitudeWGS84 + '}';
        let icon = '';

        if (value.stitched_permit_id === toggle.permitId) {
            icon = 'https://quickevict.nyc3.digitaloceanspaces.com/background1.jpg';
        } else {
            icon = 'https://quickevict.nyc3.digitaloceanspaces.com/wellIcon.png';
        }

        let position = new google.maps.LatLng(JSON.parse(surfaceLng + ',' + surfaceLat));
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: value.Grantor,
            icon: icon,
        });

        // Allow each marker to have an info window
        google.maps.event.addListener(marker, 'click', (function (marker) {
            return function () {
                infoWindow.setContent('<div class="info_content">' +
                    '<h4>Well Name: ' + value.WellName + '</h4>' +
                    '<h4>Well Number: ' + value.WellNumber + '</h4>' +
                    '<h5>Status: ' + value.WellStatus + '</h5>' +
                    '<h5>Drill Type: ' + value.DrillType + '</h5>' +
                    '<h5>Depth: ' + value.MeasuredDepth + '</h5>' +
                    '</div>');
                infoWindow.open(map, marker);
            }
        })(marker));
    });

    $('#refresh_lease_data_btn').on('click', function() {
        let leaseNamesString = '';

       $.each($('#lease_name_select')[0].selectedOptions, function(key,value) {
           leaseNamesString += value.value + '|';
       });
        leaseNamesString = leaseNamesString.slice(0, -1);

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
            url: '/mineral-owners/updateLeaseNames',
            data: {
                permitId: $('#permit_id').val(),
                leaseNames: leaseNamesString
            },
            success: function success(data) {
                console.log(data);
                console.log($('#permit_id').val());
                if (data === $('#permit_id').val()) {
                    console.log('im in here');
                    location.reload();
                }

            },
            error: function error(data) {
                console.log(data);
            }
        });
    });



    $('#refresh_well_data_btn').on('click', function() {
        let wellNamesString = '';

        $.each($('#well_name_select')[0].selectedOptions, function(key,value) {
            wellNamesString += value.value + '|';
        });
        wellNamesString = wellNamesString.slice(0, -1);

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
            url: '/mineral-owners/updateWellNames',
            data: {
                permitId: $('#permit_id').val(),
                wellNames: wellNamesString
            },
            success: function success(data) {

                if (data === $('#permit_id').val()) {
                    location.reload();
                }
            },
            error: function error(data) {
                console.log(data);
            }
        });
    });









    /*              WELLS PRODUCTION DETAILS              */
    let table = $('.wells_table').DataTable();

    // Add event listener for opening and closing details
    $('.wells_table tbody').on('click', 'td.details-control', function () {
        let tr = $(this).closest('tr');
        let row = table.row( tr );

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
             url: '/mineral-owners/get/getWellDetails',
             data: {
                 id: $(this)[0].id,
                 leaseName: $('#lease_name').val()
             },
             success: function success(data) {
                 console.log(data);
                 let tableBody = '<table class="table table-bordered table-hover" style="padding-left:50px;"><thead>' +
                     '<tr>' +
                     '<th class="text-center">Cum Gas</th>' +
                     '<th class="text-center">Cum Oil</th>' +
                     '<th class="text-center">Measured Depth</th>' +
                     '<th class="text-center">Abstract</th>' +
                     '<th class="text-center">Range</th>' +
                     '<th class="text-center">District</th>' +
                     '<th class="text-center">Section</th>' +
                     '<th class="text-center">Prod Date</th>' +
                     '</tr>' +
                     '</thead>';
                 let tableRows = '';

                 $.each(data, function( key, value ) {
                     let firstProdDate = '';
                     let prodDate = '';

                     if (value.FirstProdDate !== null) {
                         prodDate = value.FirstProdDate;
                         let prodDateArray = prodDate.split('T');
                         firstProdDate = prodDateArray[0];
                     } else {
                         firstProdDate = 'N/A';
                     }

                     tableRows += '<tr>' +
                         '<td class="text-center">'+value.CumGas+'</td>' +
                         '<td class="text-center">'+value.CumOil+'</td>' +
                         '<td class="text-center">'+value.MeasuredDepth+'</td>' +
                         '<td class="text-center">'+value.Abstract+'</td>' +
                         '<td class="text-center">'+value.Range+'</td>' +
                         '<td class="text-center">'+value.District+'</td>' +
                         '<td class="text-center">'+value.Section+'</td>' +
                         '<td class="text-center">'+firstProdDate+'</td>' +
                         '</tr>'
                 });
                 tableBody += tableRows + '</table>';

                 if ( row.child.isShown() ) {
                     // This row is already open - close it
                     row.child.hide();
                     tr.removeClass('shown');
                 }
                 else {
                     // Open this row
                     row.child( tableBody ).show();
                     tr.addClass('shown');
                 }
             },
             error: function error(data) {
                 console.log(data);
             }
         });
    });




    /*                  FUNCTIONS                   */
    function getOwnerNotes( ownerId ) {

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
            url: '/mineral-owners/getNotes',
            data: {
                ownerId: ownerId,
                leaseName: $('#lease_name').val()
            },
            success: function success(data) {
                if (data !== undefined && data !== '') {
                    let updatedNotes = '';

                    $.each(data, function (key, value) {
                        updatedNotes += '<span>'+value.notes+'</span>';
                    });
                    updatedNotes = $('<span>' + updatedNotes + '</span>');

                    $('#previous_owner_notes_'+ownerId).empty().append(updatedNotes.html());
                } else {
                    $('#previous_owner_notes_'+ownerId).empty();
                }
            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Make sure You Selected an Owner').text('Note Submission Error. Make sure You Selected an Owner');
            }
        });
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

});