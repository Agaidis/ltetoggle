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

});