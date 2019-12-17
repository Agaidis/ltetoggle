$(document).ready(function () {

    let globalPermitId = '';

    $('#permit_table').DataTable({
        "pagingType": "simple",
        "aaSorting": []
    }).on('click', '.view_permit', function () {
        var id = $(this)[0].id;
        var splitId = id.split('_');
        var permitId = splitId[1];
        console.log(permitId);
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
            dataType: 'json',
            data: {
                permitId: permitId
            },
            success: function success(data) {
                console.log(data);
                let survey = data[0]['survey'];
                if (data[0]['survey'] === null) {
                    survey = 'N/A';
                } else {
                    survey = data[0]['survey'];
                }

                let abstract = data[0]['abstract'];
                if (data[0]['abstract'] === null) {
                    abstract = 'N/A';
                } else {
                    abstract = data[0]['abstract'];
                }

                let block = data[0]['block'];
                if (data[0]['block'] === null) {
                    block = 'N/A';
                } else {
                    block = data[0]['block'];
                }
                let approvedDate = data[0]['approved_date'].split('T');

                // let expirPrimaryTerm = data[0]['expiration_primary_term'];
                // if (data[0]['expiration_primary_term'] === null) {
                //     expirPrimaryTerm = '--';
                // } else {
                //     expirPrimaryTerm = data[0]['expiration_primary_term'].split('T');
                // }

                $('#Abstract').text(abstract);
                $('#ApprovedDate').text(approvedDate[0]);
                $('#Block').text(block);
                $('#CountyParish').text(data[0]['county_parish'] + ', ' + data[0]['state']);
                $('#DrillType').text(data[0]['drill_type']);
                $('#LeaseName').text(data[0]['lease_name']);
                $('#OperatorAlias').text(data[0]['operator_alias']);
                $('#PermitID').text(data[0]['permit_id']);
                $('#PermitType').text(data[0]['permit_type']);
                $('#Range').text(data[0]['range']);
                $('#Section').text(data[0]['section']);
                $('#Survey').text(survey);
                $('#Township').text(data[0]['township']);
                $('#WellType').text(data[0]['well_type']);
                $('#expiration_primary_term').text('');
                $('#area_acres').text(data[0]['area_acres']);
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
            dataType: 'json',
            data: {
                permitId: permitId
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
});