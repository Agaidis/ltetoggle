$(document).ready(function () {

    $('#phone_numbers_table').DataTable().on('click', '.send_back', function() {
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
                $('.' + phoneId).remove();
            },
            error: function error(data) {
                console.log(data);
            }

        });
    }).on('click', '.insert_number', function() {
        let id = $(this)[0].id;
        let splitId = id.split('_');
        let ownerId = splitId[2];


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
                id: ownerId,
                phoneNumber: $('#insert_phone_number_' + ownerId).val(),
                phoneDesc: $('#insert_phone_desc_' + ownerId).val()
            },
            success: function success() {
                $('#insert_phone_number_' + ownerId).val('');
                $('#insert_phone_desc_' + ownerId).val('');

            },
            error: function error(data) {
                console.log(data);
            }

        });
    });
});
