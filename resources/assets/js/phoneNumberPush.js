$(document).ready(function () {

   let table = $('#phone_numbers_table').DataTable({
       "ordering": false,
       "pageLength": 50
   }).on('click', '.send_back', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let phoneId = splitId[2];

        let phoneNumber = $('#phone_number_' + phoneId).val();
        let phoneDesc = $('#phone_desc_' + phoneId).val();

        console.log(phoneId);
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
            url: '/pushed-phone-numbers/updatePhoneNumber',
            data: {
                id: phoneId,
                phoneNumber: phoneNumber,
                phoneDesc: phoneDesc

            },
            success: function success(data) {
                let rows = table
                    .rows( '.' + phoneId )
                    .remove()
                    .draw();

            },
            error: function error(data) {
                console.log(data);
            }

        });
    }).on('click', '.insert_number', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let phoneId = splitId[2];


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
            url: '/pushed-phone-numbers/insertPhoneNumber',
            data: {
                id: phoneId,
                phoneNumber: $('#insert_phone_number_' + phoneId).val(),
                phoneDesc: $('#insert_phone_desc_' + phoneId).val(),
                ownerId: $('#owner_id_' + phoneId).val()
            },
            success: function success() {
                $('#insert_phone_number_' + phoneId).val('');
                $('#insert_phone_desc_' + phoneId).val('');

            },
            error: function error(data) {
                console.log(data);
            }

        });
    });
});
