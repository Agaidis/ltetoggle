$(document).ready(function () {

    let globalPermitId = '';

    $('#permit_table').DataTable({
        "pagingType": "simple",
        "aaSorting": []
    }).on('click', '.view_permit', function () {
        var id = $(this)[0].id;
        var splitId = id.split('_');
        var permitId = splitId[1];
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
                $('#API10').text(data[0]['API10']);
                $('#API12').text(data[0]['API12']);
                $('#Abstract').text(data[0]['Abstract']);
                $('#AmendmentFiledDate').text(data[0]['AmendmentFiledDate']);
                $('#ApprovedDate').text(data[0]['ApprovedDate']);
                $('#Block').text(data[0]['Block']);
                $('#BottomHoleLatitudeWGS84').text(data[0]['BottomHoleLatitudeWGS84']);
                $('#BottomHoleLongitudeWGS84').text(data[0]['BottomHoleLongitudeWGS84']);
                $('#ContactName').text(data[0]['ContactName']);
                $('#ContactPhone').text(data[0]['ContactPhone']);
                $('#CountyParish').text(data[0]['CountyParish']);
                $('#CreatedDate').text(data[0]['CreatedDate']);
                $('#DeletedDate').text(data[0]['DeletedDate']);
                $('#District').text(data[0]['District']);
                $('#DrillType').text(data[0]['DrillType']);
                $('#ExpiredDate').text(data[0]['ExpiredDate']);
                $('#Field').text(data[0]['Field']);
                $('#Formation').text(data[0]['Formation']);
                $('#H2SArea').text(data[0]['H2SArea']);
                $('#LeaseName').text(data[0]['LeaseName']);
                $('#LeaseNumber').text(data[0]['LeaseNumber']);
                $('#OFSRegion').text(data[0]['OFSRegion']);
                $('#OperatorAddress').text(data[0]['OperatorAddress']);
                $('#OperatorAlias').text(data[0]['OperatorAlias']);
                $('#OperatorCity').text(data[0]['OperatorCity']);
                $('#OperatorCity30mi').text(data[0]['OperatorCity30mi']);
                $('#OperatorCity50mi').text(data[0]['OperatorCity50mi']);
                $('#OperatorState').text(data[0]['OperatorState']);
                $('#OperatorZip').text(data[0]['OperatorZip']);
                $('#OrigApprovedDate').text(data[0]['OrigApprovedDate']);
                $('#PermitDepth').text(data[0]['PermitDepth']);
                $('#PermitDepthUOM').text(data[0]['PermitDepthUOM']);
                $('#PermitID').text(data[0]['PermitID']);
                $('#PermitNumber').text(data[0]['PermitNumber']);
                $('#PermitStatus').text(data[0]['PermitStatus']);
                $('#PermitType').text(data[0]['PermitType']);
                $('#Range').text(data[0]['Range']);
                $('#ReportedOperator').text(data[0]['ReportedOperator']);
                $('#Section').text(data[0]['Section']);
                $('#StateProvince').text(data[0]['StateProvince']);
                $('#SubmittedDate').text(data[0]['SubmittedDate']);
                $('#SurfaceLatitudeWGS84').text(data[0]['SurfaceLatitudeWGS84']);
                $('#SurfaceLongitudeWGS84').text(data[0]['SurfaceLongitudeWGS84']);
                $('#Survey').text(data[0]['Survey']);
                $('#Township').text(data[0]['Township']);
                $('#TrueVerticalDepth').text(data[0]['TrueVerticalDepth']);
                $('#TrueVerticalDepthUOM').text(data[0]['TrueVerticalDepthUOM']);
                $('#UpdatedDate').text(data[0]['UpdatedDate']);
                $('#WGID').text(data[0]['WGID']);
                $('#WellNumber').text(data[0]['WellNumber']);
                $('#WellStatus').text(data[0]['WellStatus']);
                $('#WellType').text(data[0]['WellType']);
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
        console.log('dfg');
        console.log(assignee);
        let splitId = id.split('_');
        let permitId = splitId[1];
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