$(document).ready(function () {

    /* HIGH PRIORITY WELLBORE TABLE */

    $('.wellbore_owner_follow_up').datepicker();

    $('.high_priority_wellbore_table').DataTable( {
        "pagingType": "simple",
        "pageLength" : 25,
        "aaSorting": [],
        "order": [[ 1, "desc" ]]
    }).on('change', '.owner_assignee', function() {
        let id = $(this)[0].id;
        let assignee = $(this)[0].value;
        let ownerId = id.split('_');

        changeAssignee(assignee, ownerId[1]);


    }).on('click', '.owner_row', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[2];
        $('#owner_id').val(splitId[2]);

        selectRow(ownerId);

    }).on('click', '.view_owner', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');

        viewOwner(splitId[1]);

    }).on('change', '.wellbore_dropdown', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let wellType = $(this)[0].value;
        $('#owner_id').val(splitId[2]);

        changeWellbore(splitId[2], wellType);


    }).on('click', '.add_phone_btn', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        $('#owner_id').val(splitId[2]);

        openPhoneModal(splitId[2]);
    }).on('change', '.wellbore_owner_follow_up',  function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let uniqueId = splitId[3];

        ownerFollowupDateChange(uniqueId);
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

        changeAssignee(assignee, ownerId[1]);

    }).on('click', '.owner_row', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[2];
        $('#owner_id').val(splitId[2]);

        selectRow(ownerId);

    }).on('click', '.view_owner', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');

        viewOwner(splitId[1]);

    }).on('change', '.wellbore_dropdown', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let wellType = $(this)[0].value;
        $('#owner_id').val(splitId[2]);

        changeWellbore(splitId[2], wellType);

    }).on('click', '.add_phone_btn', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        $('#owner_id').val(splitId[2]);

        openPhoneModal(splitId[2]);
    }).on('change', '.wellbore_owner_follow_up',  function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let uniqueId = splitId[3];

        console.log(uniqueId);

        ownerFollowupDateChange(uniqueId);
    });




    $('.phone_container').on('click', '.soft_delete_phone', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let uniqueId = splitId[2];

        softDeletePhone(uniqueId);

    }).on('click', '.push_back_phone', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let uniqueId = splitId[3];

        pushBackPhone(uniqueId);
    });


    $('.current_phones').on('click', '.wellbore_submit_phone_btn', function() {
        submitPhone();
    });








    /*              FUNCTIONS               */

    function changeAssignee(assignee, ownerId) {
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
                ownerId: ownerId,
                assigneeId: assignee
            },
            success: function success(data) {
                console.log(data);
            },
            error: function error(data) {
                console.log(data);
            }
        });
    }

    function selectRow(ownerId) {
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
                if (data !== undefined && data !== '') {
                    let updatedNotes = '';

                    $.each(data, function (key, value) {
                        updatedNotes += '<span>'+value.notes+'</span>';
                    });
                    updatedNotes = $('<span>' + updatedNotes + '</span>');

                    $('.previous_owner_notes').empty().append(updatedNotes.html());
                } else {
                    $('.previous_owner_notes').empty();
                }
            },
            error: function error(data) {
                $('.owner_notes').val('Note Submission Error. Make sure You Selected an Owner').text('Note Submission Error. Make sure You Selected an Owner');
            }
        });
    }

    function viewOwner(ownerId) {
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
    }


    function changeWellbore(ownerId, wellType) {
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
    }

    function openPhoneModal(ownerId) {
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
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
    }

    function softDeletePhone(uniqueId) {
        console.log($('#owner_id').val());
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
                $('#phone_'+uniqueId).remove();

            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
    }

    function pushBackPhone(uniqueId) {
        console.log($('#owner_id').val());
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
    }

    function submitPhone() {
        console.log('IM IN HERE ATLEAST!');
        console.log($('#owner_id').val());
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
                ownerId: $('#owner_id').val(),
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
    }

    function ownerFollowupDateChange(uniqueId) {

        let date = $('#owner_follow_up_' + uniqueId).val();

        console.log(uniqueId);
        console.log(date);

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
    }




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