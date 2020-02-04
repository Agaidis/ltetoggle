$(document).ready(function () {

    $( function() {
        let dateFormat = "mm/dd/yy",
            from = $( "#from" )
                .datepicker({
                    defaultDate: "-7",
                    changeMonth: true,
                    numberOfMonths: 1,
                    maxDate: 0
                })
                .on( "change", function() {
                    to.datepicker( "option", "minDate", getDate( this ) );
                }),
            to = $( "#to" ).datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 2,
                maxDate: 0
            })
                .on( "change", function() {
                    from.datepicker( "option", "maxDate", getDate( this ) );
                });

        function getDate( element ) {
            let date;
            try {
                date = $.datepicker.parseDate( dateFormat, element.value );
            } catch( error ) {
                date = null;
            }

            return date;
        }
    } );

    $('#update_permit_btn').on('click', function() {
        let county = $('#county_select').val();

        console.log(county);
        if (county === null) {
            alert('Please select a county');
        } else {
            $('.loader').css('display', 'inline-block');

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
                url: '/admin/updatePermits',
                data: {
                    county: county
                },
                success: function success(data) {
                    $('.loader').css('display', 'none');
                    console.log(data);


                    if (data === 'success') {
                        let messages = $('.messages');
                        let successHtml = '<div class="alert alert-success">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></</strong> Database has successfully been updated!' +
                            '</div>';
                        $(messages).html(successHtml);
                    } else if (data === 'error') {
                        let messages = $('.alert-danger');
                        let successHtml = '<div class="alert alert-danger">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<strong><i class="glyphicon glyphicon-ok-sign push-5-r"></</strong> There was a problem Updating Database' +
                            '</div>';
                        $(messages).html(successHtml);
                    }


                },
                error: function error(data) {
                    console.log(data);
                }
            });
        }
    });

});