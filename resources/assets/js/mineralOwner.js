$(document).ready(function () {
    let globalOwnerId = '';
    let globalOwnerName = '';

    $('.previous_permit_notes').html($('#hidden_permit_notes').val());

    $('#owner_table').DataTable({
        "pagingType": "simple",
        "pageLength" : 5,
        "aaSorting": [],
        "order": [[ 4, "desc" ]]
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
            url: '/mineral-owner/updateAssignee',
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
    }).on('click', '.owner_row', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[2];
        globalOwnerId = ownerId;

        $('.owner_row').css('background-color', 'white');
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
            url: '/mineral-owners/getNotes',
            data: {
                ownerId: ownerId,
                leaseName: $('#lease_name').val()
            },
            success: function success(data) {
                console.log(data);
                let updatedNotes = $('<span>'+data+'</span>');

                $('.previous_owner_notes').empty().append(updatedNotes.html());
            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
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
    }).on('click', '.view_owner', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[1];

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
    $('.update_owner_notes_btn').on('click', function() {
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
                leaseName: $('#lease_name').val(),
                notes: $('.owner_notes').val()
            },
            success: function success(data) {
                let updatedNotes = $('<span>'+data+'</span>');

                $('.previous_owner_notes').empty().append(updatedNotes.html());
                $('.owner_notes').val('').text('');
            },
            error: function error(data) {
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
    });

    $('.add_phone_btn').on('click', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerName = splitId[2];
        globalOwnerName = ownerName;

        $('#new_phone_desc').val('').text('');
        $('#new_phone_number').val('').text('');
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
                uniqueId: uniqueId,
                ownerName: $('#phone_owner_' + uniqueId).val(),
                phoneDesc: $('#phone_desc_' + uniqueId).val(),
                phoneNumber: $('#phone_number_' + uniqueId).val()
            },
            success: function success(data) {
                console.log(data);
                $('#phone_'+data[0]).remove();

            },
            error: function error(data) {
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });

    });

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
                let min=9999;
                let max=999999999999;
                let random =
                    Math.floor(Math.random() * (+max - +min)) + +min;

                console.log(data);
                let updatedPhoneNumbers = $('<div><span id="phone_'+random+'" style="padding: 2%;">' +
                    '<input type="hidden" id="phone_owner_'+random+'" value="'+data.owner_name+'"/>' +
                    '<input type="hidden" id="phone_number_'+random+'" value="'+data.phone_number+'" />' +
                    '<input type="hidden" id="phone_desc_'+random+'" value="'+data.phone_desc+'"/>' +
                    '<span style="font-weight: bold;">'+data.phone_desc+': </span>' +
                    '<span><a href="tel:'+data.phone_number+'">'+data.phone_number+'</a></span>' +
                    '<span style="cursor:pointer; color:red; margin-left:5%;" class="soft_delete_phone fas fa-trash" id="soft_delete_'+random+'"></span>' +
                    '</span></div>');

                $('#phone_container_' + globalOwnerId).append(updatedPhoneNumbers.html());
            },
            error: function error(data) {
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
    });
});