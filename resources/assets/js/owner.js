$(document).ready(function () {

    let phoneTable = $('#owner_phone_table').DataTable({
        "pagingType": "simple",
        "pageLength": 10,
        "aaSorting": [],
    });

    let ownerLeaseTable = $('#owner_lease_table').DataTable( {
        "pagingType": "simple",
        "pageLength": 5,
        "aaSorting": [],
    });
});