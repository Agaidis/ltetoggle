$(document).ready(function () {

    $('#table_one').DataTable( {
        "pagingType": "simple",
        "aaSorting": []
    });

    $('#table_two').DataTable( {
        "pagingType": "simple",
        "aaSorting": []
    });

    $('.btn-danger').on('click', function() {
        confirm('Are you sure you want do delete this row?')
    });
});

