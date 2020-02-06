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

    $('.owner_table').DataTable({
        "pagingType": "simple",
        "pageLength" : 25,
        "aaSorting": [],
        "order": [[ 5, "desc" ]]
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
                $('#net_royalty_acres').text(data.owner_decimal_interest % .125 * $('.acreage').val())
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
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
    });

    $('.previous_owner_notes').on('mouseover', '.owner_note', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[1];

        $('#' + id).css('background-color', 'lightgrey');
        $('#delete_owner_note_'+ownerId).css('display', 'inherit');
    }).on('mouseleave', '.owner_note', function() {
        $('.delete_owner_note').css('display', 'none');
        $('.owner_note').css('background-color', '#BCE8A6');
    }).on('click', '.delete_owner_note', function() {
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

                    $('.previous_owner_notes').empty().append(updatedNotes.html());
                },
                error: function error(data) {
                    console.log(data);
                }
            });
        }
    });

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
                    console.log(value.id);
                    phoneNumbers += '<span><div id="phone_'+value.id+'" style="padding: 2%;">'+
                        '<span style="font-weight: bold;">'+value.phone_desc+': </span>'+
                        '<span><a href="tel:'+value.id+'">'+value.phone_number+' </a></span>'+
                        '<span style="cursor:pointer; color:red; margin-left:5%;" class="soft_delete_phone fas fa-trash" id="soft_delete_'+value.id+'" "></span>'+
                        '</div></span>';
                });
                console.log(phoneNumbers);
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

                $('#new_phone_desc').val('').text('');
                $('#new_phone_number').val('').text('');

                let phoneNumber = '<span><div id="phone_'+data.id+'" style="padding: 2%;">'+
                    '<span style="font-weight: bold;">'+data.phone_desc+': </span>'+
                    '<span><a href="tel:'+data.id+'">'+data.phone_number+' </a></span>'+
                    '<span style="cursor:pointer; color:red; margin-left:5%;" class="soft_delete_phone fas fa-trash" id="soft_delete_'+data.id+'" "></span>'+
                    '</div></span>';

            $('.phone_container').append($(phoneNumber).html());

            },
            error: function error(data) {
                console.log(data);
                $('.owner_notes').val('Note Submission Error. Contact Dev Team').text('Note Submission Error. Contact Dev Team');
            }
        });
    });

    $('.acreage').on('focusout', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let uniqueId = splitId[1];
        console.log(uniqueId);

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
});