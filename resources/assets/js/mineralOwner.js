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
      //  globalOwnerId = ownerId;


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
                    '<h3 style="text-align: center;">Additional Info</h3>' +
                    '<div class="containers">' +
                    '<label class="addit_labels" for="first_prod_'+ownerId+'">First Prod Date: </label>' +
                    '<span id="first_prod_'+ownerId+'"></span><br>' +
                    '<label class="addit_labels" for="last_prod_'+ownerId+'">Last Prod Date: </label>' +
                    '<span id="last_prod_'+ownerId+'"></span><br>' +
                    '<label class="addit_labels" for="cum_prod_oil_'+ownerId+'">Cumulative Prod Oil: </label>' +
                    '<span id="cum_prod_oil_'+ownerId+'"></span><br>' +
                    '<label class="addit_labels" for="cum_prod_gas_'+ownerId+'">Cumulative Prod Gas: </label>' +
                    '<span id="cum_prod_gas_'+ownerId+'"></span><br>' +
                    '<label class="addit_labels" for="active_well_count_'+ownerId+'">Active Well Count: </label>' +
                    '<span id="active_well_count_'+ownerId+'"></span><br>' +
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
                    '</div></div></div>' +
                    '<div class="col-md-6">' +
                    '<div style="text-align:center;" class="col-md-6">' +
                    '<label style="font-size:20px; font-weight:bold;" for="notes">Owner Notes</label>' +
                    '<div class="previous_owner_notes" id="previous_owner_notes_'+ownerId+'" name="previous_owner_notes" contenteditable="false"></div>' +
                    '</div>' +
                    '<div style="text-align:center;" class="col-md-6">' +
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

                $('#first_prod_'+ownerId).text(' ' + data.first_prod_date);
                $('#last_prod_'+ownerId).text(' ' + data.last_prod_date);
                $('#cum_prod_oil_'+ownerId).text(' ' + data.cum_prod_oil);
                $('#cum_prod_gas_'+ownerId).text(' ' + data.cum_prod_gas);
                $('#active_well_count_'+ownerId).text(' ' + data.active_well_count);
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

                totalPriceForInterest = numberWithCommas(totalPriceForInterest);

                $('#total_price_for_interest_'+ownerId).val( '$' + totalPriceForInterest);


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

        totalPriceForInterest = numberWithCommas(totalPriceForInterest);

        $('#total_price_for_interest_' +ownerId).val( '$' + totalPriceForInterest);

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
    });


    /*                  PHONE CAPABILITIES                  */
    $('.add_phone_btn').on('click', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerName = splitId[2];
        globalOwnerName = ownerName;

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
                ownerName: ownerName
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

    });;

    $('.submit_phone_btn').on('click', function() {
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
                ownerName: globalOwnerName,
                phoneDesc: $('#new_phone_desc').val(),
                phoneNumber: $('#new_phone_number').val()
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
                 govId: $(this)[0].id,
                 leaseName: $('#lease_name').val()
             },
             success: function success(data) {
                 console.log(data);
                 let tableBody = '<table class="table table-bordered table-hover" style="padding-left:50px;"><thead>' +
                     '<tr>' +
                     '<th class="text-center">Avg Gas</th>' +
                     '<th class="text-center">Avg Oil</th>' +
                     '<th class="text-center">Cum Gas</th>' +
                     '<th class="text-center">Cum Oil</th>' +
                     '<th class="text-center">Gas</th>' +
                     '<th class="text-center">Prod Date</th>' +
                     '</tr>' +
                     '</thead>';
                 let tableRows = '';

                 $.each(data, function( key, value ) {
                     let prodDate = value.prod_date;
                     prodDate = prodDate.split('T');
                     tableRows += '<tr>' +
                         '<td class="text-center">'+value.avg_gas+'</td>' +
                         '<td class="text-center">'+value.avg_oil+'</td>' +
                         '<td class="text-center">'+value.cum_gas+'</td>' +
                         '<td class="text-center">'+value.cum_oil+'</td>' +
                         '<td class="text-center">'+value.gas+'</td>' +
                         '<td class="text-center">'+prodDate[0]+'</td>' +
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