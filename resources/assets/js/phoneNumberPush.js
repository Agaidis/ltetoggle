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
                $('#phone_number_row_'+ phoneId).remove();
            },
            error: function error(data) {
                console.log(data);
            }

        });
    });
});
