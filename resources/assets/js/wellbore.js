$(document).ready(function () {

    /* HIGH PRIORITY WELLBORE TABLE */

    $('.wellbore_owner_follow_up').datepicker();

    let highPriorityTable = $('.high_priority_wellbore_table').DataTable( {
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
        $('#owner_id').val(splitId[2]);

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
    }).on('click', 'td.wellbore-details-control', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[1];
        let tr = $(this).closest('tr');
        let row = highPriorityTable.row( tr );
        getNotes(ownerId, tr, row)

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
                leaseName: $('#lease_name_' + ownerId).val(),
                notes: $('#owner_notes_' +ownerId).val()
            },
            success: function success(data) {
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
    });

    /* LOWER PRIORITY WELLBORE TABLE */

    let lowPriorityTable = $('.low_priority_wellbore_table').DataTable( {
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
        $('#owner_id').val(splitId[2]);

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
    }).on('click', 'td.wellbore-details-control', function () {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[1];
        let tr = $(this).closest('tr');
        let row = lowPriorityTable.row( tr );

        getNotes(ownerId, tr, row)

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
                leaseName: $('#lease_name_' + ownerId).val(),
                notes: $('#owner_notes_' +ownerId).val()
            },
            success: function success(data) {
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
        console.log('hahaha');
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

    function getNotes(ownerId, tr, row) {
        console.log($('#lease_name_' + ownerId).val());
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
            url: '/mineral-owners/getNotes',
            data: {
                ownerId: ownerId,
                leaseName: $('#lease_name_' + ownerId).val()
            },
            success: function success(data) {

                console.log(data);
               let noteContainer = '<div class="col-md-6">' +
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
                    row.child(noteContainer).show();
                    tr.addClass('shown');
                }

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
                $('.owner_notes').val('Note Submission Error. Make sure You Selected an Owner').text('Note Submission Error. Make sure You Selected an Owner');
            }
        });
    }

    function updateNotes( permitId ) {
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
    }

    function deleteNote( permitId, noteId, response ) {
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
                leaseName: $('#lease_name_' + $('#owner_id').val()).val()
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
});