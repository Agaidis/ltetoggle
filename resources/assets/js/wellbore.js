$(document).ready(function () {

    let globalOwnerId = '';

    /* HIGH PRIORITY WELLBORE TABLE */

    $('.high_priority_wellbore_table').DataTable( {
        "pagingType": "simple",
        "pageLength" : 25,
        "aaSorting": [],
        "order": [[ 1, "desc" ]]
    }).on('change', '.owner_assignee', function() {
        let id = $(this)[0].id;
        let assignee = $(this)[0].value;
        let ownerId = id.split('_');

        if (assignee === '0') {
            $(this).removeClass('assigned_style');
        } else {
            $(this).addClass('assigned_style');
        }

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
                console.log(data);
            },
            error: function error(data) {
                console.log(data);
            }
        });
    }).on('click', '.owner_row', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[2];
        console.log(ownerId);
        globalOwnerId = ownerId;

       // $('.owner_row').css('background-color', 'white');
      //  $('#' + id).css('background-color', '#e3e3d1');

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
                leaseName: $('#lease_name_' + ownerId).val()
            },
            success: function success(data) {
                console.log(data);
                if (data !== undefined && data !== '') {
                    let updatedNotes = '';

                    $.each(data, function (key, value) {
                        updatedNotes += '<span>'+value.notes+'</span>';
                    });
                    updatedNotes = $('<span>' + updatedNotes + '</span>');
                    console.log(updatedNotes);

                    $('.previous_owner_notes').empty().append(updatedNotes.html());
                } else {
                    $('.previous_owner_notes').empty();
                }
            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Make sure You Selected an Owner').text('Note Submission Error. Make sure You Selected an Owner');
            }
        });
    }).on('click', '.view_owner', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[1];
        console.log(ownerId);


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
                console.log(data);
                let nameAddressString = data.owner + '<br>' + data.owner_address + '<br>' + data.owner_city + ',' + data.owner_zip;
                $('#owner_name').text(data.owner);
                $('#name_address').append(nameAddressString);
                $('#lease_name').text(data.lease_name);
                $('#owner_price').val(data.price);
                $('#lease_description').text(data.lease_description);
                $('#rrc_lease_number').text(data.rrc_lease_number);
                $('#decimal_interest').text(data.owner_decimal_interest);
                $('#interest_type').text(data.owner_interest_type);
                $('#tax_value').text(data.tax_value);
                $('#first_prod').text(data.first_prod_date);
                $('#last_prod').text(data.last_prod_date);
                $('#cum_prod_oil').text(data.cum_prod_oil);
                $('#cum_prod_gas').text(data.cum_prod_gas);
                $('#active_well_count').text(data.active_well_count);

                let netRoyaltyAcres = data.owner_decimal_interest / .125 * $('.acreage').val();
                netRoyaltyAcres = netRoyaltyAcres.toFixed(4);
                $('#net_royalty_acres').text(netRoyaltyAcres)
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
                console.log(data);
            },
            error: function error(data) {
                console.log(data);
            }
        });
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

















    /* LOWER PRIORITY WELLBORE TABLE */

    $('.low_priority_wellbore_table').DataTable( {
        "pagingType": "simple",
        "pageLength" : 25,
        "aaSorting": [],
        "order": [[ 1, "desc" ]]
    }).on('change', '.owner_assignee', function() {
        let id = $(this)[0].id;
        let assignee = $(this)[0].value;
        let ownerId = id.split('_');

        if (assignee === '0') {
            $(this).removeClass('assigned_style');
        } else {
            $(this).addClass('assigned_style');
        }

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
                console.log(data);
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

        console.log(ownerId);
        console.log(globalOwnerId);

      //  $('.owner_row').css('background-color', 'white');
        //$('#' + id).css('background-color', '#e3e3d1');

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
                leaseName: $('#lease_name_' + ownerId).val()
            },
            success: function success(data) {
                console.log(data);
                if (data !== undefined && data !== '') {
                    let updatedNotes = '';

                    $.each(data, function (key, value) {
                        updatedNotes += '<span>'+value.notes+'</span>';
                    });
                    updatedNotes = $('<span>' + updatedNotes + '</span>');
                    console.log(updatedNotes);

                    $('.previous_owner_notes').empty().append(updatedNotes.html());
                } else {
                    $('.previous_owner_notes').empty();
                }
            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Make sure You Selected an Owner').text('Note Submission Error. Make sure You Selected an Owner');
            }
        });
    }).on('click', '.view_owner', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[1];
        console.log(ownerId);

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
                console.log(data);
                let nameAddressString = data.owner + '<br>' + data.owner_address + '<br>' + data.owner_city + ',' + data.owner_zip;
                $('#owner_name').text(data.owner);
                $('#name_address').append(nameAddressString);
                $('#lease_name').text(data.lease_name);
                $('#owner_price').val(data.price);
                $('#lease_description').text(data.lease_description);
                $('#rrc_lease_number').text(data.rrc_lease_number);
                $('#decimal_interest').text(data.owner_decimal_interest);
                $('#interest_type').text(data.owner_interest_type);
                $('#tax_value').text(data.tax_value);
                $('#first_prod').text(data.first_prod_date);
                $('#last_prod').text(data.last_prod_date);
                $('#cum_prod_oil').text(data.cum_prod_oil);
                $('#cum_prod_gas').text(data.cum_prod_gas);
                $('#active_well_count').text(data.active_well_count);

                let netRoyaltyAcres = data.owner_decimal_interest / .125 * $('.acreage').val();
                netRoyaltyAcres = netRoyaltyAcres.toFixed(4);
                $('#net_royalty_acres').text(netRoyaltyAcres)
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
                console.log(data);
            },
            error: function error(data) {
                console.log(data);
            }
        });
    });



    //UPDATE OWNER NOTES
    $('.update_owner_notes_wellbore_btn').on('click', function() {
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
                ownerId: globalOwnerId,
                leaseName: $('#lease_name_' + globalOwnerId).val(),
                notes: $('.owner_notes').val()
            },
            success: function success(data) {
                let updatedNotes = '';

                $.each(data, function (key, value) {
                    updatedNotes += '<span>'+value.notes+'</span>';
                });
                updatedNotes = $('<span>' + updatedNotes + '</span>');

                $('.previous_owner_notes').empty().append(updatedNotes.html());
                $('.owner_notes').val('').text('');
            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Make sure You Selected an Owner').text('Note Submission Error. Make sure You Selected an Owner');
            }
        });
    });
});