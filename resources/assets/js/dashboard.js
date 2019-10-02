$(document).ready(function () {

    let globalLeaseId = '';

    $('#lease_table').DataTable({
        "pagingType": "simple",
        "aaSorting": []
    }).on('click', '.view_lease', function () {
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
            url: '/dashboard/getPermitDetails',
            dataType: 'json',
            data: {
                leaseId: leaseId
            },
            success: function success(data) {
                console.log(data);
                $('#areaAcres').text(data[0]['AreaAcres']);
                $('#BLM').text(data[0]['BLM']);
                $('#Bonus').text(data[0]['Bonus']);
                $('#CentroidLatitude').text(data[0]['CentroidLatitude']);
                $('#CentroidLongitude').text(data[0]['CentroidLongitude']);
                $('#CountyParish').text(data[0]['CountyParish']);
                $('#CreatedDate').text(data[0]['CreatedDate']);
                $('#DIBasin').text(data[0]['DIBasin']);
                $('#DILink').text(data[0]['DILink']);
                $('#DIPlay').text(data[0]['DIPlay']);
                $('#DISubPlay').text(data[0]['DISubPlay']);
                $('#DeletedDate').text(data[0]['DeletedDate']);
                $('#DepthClauseAvailable').text(data[0]['DepthClauseAvailable']);
                $('#DepthClauseTypes').text(data[0]['DepthClauseTypes']);
                $('#EffectiveDate').text(data[0]['EffectiveDate']);
                $('#ExpirationofPrimaryTerm').text(data[0]['ExpirationofPrimaryTerm']);
                $('#ExtBonus').text(data[0]['ExtBonus']);
                $('#ExtTermMonths').text(data[0]['ExtTermMonths']);
                $('#Geometry').text(data[0]['Geometry']);
                $('#Grantee').text(data[0]['Grantee']);
                $('#GranteeAddress').text(data[0]['GranteeAddress']);
                $('#GranteeAlias').text(data[0]['GranteeAlias']);
                $('#InstrumentDate').text(data[0]['InstrumentDate']);
                $('#InstrumentType').text(data[0]['InstrumentType']);
                $('#LeaseId').text(data[0]['LeaseId']);
                $('#MajorityAssignmentEffectiveDate').text(data[0]['MajorityAssignmentEffectiveDate']);
                $('#MajorityAssignmentVolPage').text(data[0]['MajorityAssignmentVolPage']);
                $('#MajorityLegalAssignee').text(data[0]['MajorityLegalAssignee']);
                $('#MajorityLegalAssigneeInterest').text(data[0]['MajorityLegalAssigneeInterest']);
                $('#MaxDepth').text(data[0]['MaxDepth']);
                $('#MinDepth').text(data[0]['MinDepth']);
                $('#Nomination').text(data[0]['Nomination']);
                $('#OptionsExtensions').text(data[0]['OptionsExtensions']);
                $('#RecordDate').text(data[0]['RecordDate']);
                $('#RecordNo').text(data[0]['RecordNo']);
                $('#Remarks').text(data[0]['Remarks']);
                $('#Royalty').text(data[0]['Royalty']);
                $('#SpatialAssignee').text(data[0]['SpatialAssignee']);
                $('#State').text(data[0]['State']);
                $('#StateLease').text(data[0]['StateLease']);
                $('#TermMonths').text(data[0]['TermMonths']);
                $('#UpdatedDate').text(data[0]['UpdatedDate']);
                $('#VolPage').text(data[0]['VolPage']);
            },
            error: function error(data) {
                console.log(data);
            }
        });
    }).on('click', '.lease_row', function() {
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
            url: '/dashboard/getNotes',
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
    });

    $('.update_lease_notes_btn').on('click', function() {
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
            url: '/dashboard/updateNotes',
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

    $('#wellbore_table').DataTable( {
        "pagingType": "simple",
        "aaSorting": []
    });
});
