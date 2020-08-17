$(document).ready(function () {
    if (location.href.split('/')[3] === 'non-producing-mineral-owner') {


        $('#refresh_well_data_btn').on('click', function() {
            let wellNamesString = '';

            $.each($('#well_name_select')[0].selectedOptions, function(key,value) {
                wellNamesString += value.value + '|';
            });
            wellNamesString = wellNamesString.slice(0, -1);

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
                url: '/mineral-owners/updateWellNames',
                data: {
                    permitId: $('#permit_id').val(),
                    wellNames: wellNamesString
                },
                success: function success(data) {

                    if (data === $('#permit_id').val()) {
                        location.reload();
                    }
                },
                error: function error(data) {
                    console.log(data);
                }
            });
        });
        let map;
        let bounds = new google.maps.LatLngBounds();




        if (toggle.allRelatedPermits !== undefined && toggle.allRelatedPermits !== 'undefined' && toggle.allRelatedPermits.length !== 0) {
            let surfaceLng = '{"lng":' + toggle.allRelatedPermits[0].SurfaceLongitudeWGS84;
            let surfaceLat = '"lat":' + toggle.allRelatedPermits[0].SurfaceLatitudeWGS84 + '}';
            map = new google.maps.Map(document.getElementById('nonMap'), {
                zoom: 13,
                center: JSON.parse(surfaceLng + ',' + surfaceLat),
                mapTypeId: google.maps.MapTypeId.HYBRID
            });

            $.each(toggle.allRelatedPermits, function (key, value) {
                let surfaceLng = '{"lng":' + value.SurfaceLongitudeWGS84;
                let surfaceLat = '"lat":' + value.SurfaceLatitudeWGS84 + '}';
                let btmGeo = value.btm_geometry.replace(/\s/g, '').replace(/},/g, '},dd').replace('(', '').replace(')', '').split(',dd');
                let position = new google.maps.LatLng(JSON.parse(surfaceLng + ',' + surfaceLat));

                bounds.extend(position);

                let permitMarker = new google.maps.Marker({
                    position: position,
                    map: map,
                    label: 'SF'
                });

                let btmPosition = new google.maps.LatLng(JSON.parse(btmGeo));
                bounds.extend(btmPosition);

                let SurfaceMarker = new google.maps.Marker({
                    position: btmPosition,
                    map: map,
                    label: 'BM'
                });

                let flightPath = new google.maps.Polyline({
                    path: [
                        JSON.parse(surfaceLng + ',' + surfaceLat),
                        JSON.parse(btmGeo)
                    ],
                    geodesic: true,
                    strokeColor: "#ab0000",
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });

                flightPath.setMap(map);
            });
        } else {

            let surfaceLng = '{"lng":' + toggle.allWells[0].SurfaceHoleLongitudeWGS84;
            let surfaceLat = '"lat":' + toggle.allWells[0].SurfaceHoleLatitudeWGS84 + '}';

            map = new google.maps.Map(document.getElementById('nonMap'), {
                zoom: 13,
                center: JSON.parse(surfaceLng + ',' + surfaceLat),
                mapTypeId: google.maps.MapTypeId.HYBRID
            });
        }



        // Display multiple markers on a map
        let infoWindow = new google.maps.InfoWindow(), marker;

        // Loop through our array of markers & place each one on the map
        $.each(toggle.allWells, function (key, value) {

            let surfaceLng = '{"lng":' + value.SurfaceHoleLongitudeWGS84;
            let surfaceLat = '"lat":' + value.SurfaceHoleLatitudeWGS84 + '}';

            let position = new google.maps.LatLng(JSON.parse(surfaceLng + ',' + surfaceLat));
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                title: value.Grantor,
                icon: 'https://quickevict.nyc3.digitaloceanspaces.com/wellIcon.png',
            });

            // Allow each marker to have an info window
            google.maps.event.addListener(marker, 'click', (function (marker) {
                return function () {
                    infoWindow.setContent('<div class="info_content">' +
                        '<h4>Lease Name: ' + value.LeaseName + '</h4>' +
                        '<h4>Well Status: ' + value.WellStatus + '</h4>' +
                        '<h5>Range: ' + value.Range + '</h5>' +
                        '<h5>Section: ' + value.Section + '</h5>' +
                        '<h5>Township: ' + value.Township + '</h5>' +
                        '</div>');
                    infoWindow.open(map, marker);
                }
            })(marker));
        });
        let ownerTable = $('.non_producing_owner_table').DataTable({
            "pagingType": "simple",
            "pageLength": 25,
            "aaSorting": [],
            "order": [[6, "desc"]],
        }).on('click', 'td.owner-details-control', function () {
            let id = $(this)[0].id;
            let splitId = id.split('_');
            let ownerId = splitId[1];
            let tr = $(this).closest('tr');
            let row = ownerTable.row(tr);

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
                url: '/mineral-owners',
                data: {
                    id: ownerId
                },
                success: function success(data) {
                    let ownerBody = '<div class="row">' +
                        '<div class="col-md-6">' +
                        '<h3 style="text-align: center;">Well Production</h3>' +
                        '<div class="containers">' +
                        '<label class="addit_labels" for="active_well_count_' + ownerId + '">Well Count: </label>' +
                        '<span id="active_well_count_' + ownerId + '"></span><br>' +
                        '<label class="addit_labels" for="first_prod_' + ownerId + '">First Prod Date: </label>' +
                        '<span id="first_prod_' + ownerId + '"></span><br>' +
                        '<label class="addit_labels" for="last_prod_' + ownerId + '">Last Prod Date: </label>' +
                        '<span id="last_prod_' + ownerId + '"></span><br>' +
                        '<label class="addit_labels" for="years_of_prod_' + ownerId + '">Years of Production: </label>' +
                        '<span id="years_of_prod_' + ownerId + '"></span><br>' +


                        '<label class="addit_labels" for="cum_prod_oil_' + ownerId + '">Total Oil Production: </label>' +
                        '<span id="cum_prod_oil_' + ownerId + '"></span><br>' +
                        '<label class="addit_labels" for="cum_prod_gas_' + ownerId + '">Total Gas Production: </label>' +
                        '<span id="cum_prod_gas_' + ownerId + '"></span><br>' +
                        '<label class="addit_labels" for="bbls_' + ownerId + '">BBLS (OIL): </label>' +
                        '<span id="bbls_' + ownerId + '"></span><br>' +
                        '<label class="addit_labels" for="gbbls_' + ownerId + '">MNX (GAS): </label>' +
                        '<span id="gbbls_' + ownerId + '"></span><br>' +
                        '' +


                        '</div>' +
                        '</div></div>' +
                        '<div class="row"><div class="col-md-6">' +
                        '<h3 style="text-align: center;">Mineral Interest & Pricing Info.  </h3>' +
                        '<div class="containers">' +
                        '<div class="row">' +
                        '<div class="offset-2 col-md-5">' +
                        '<label class="addit_labels" for="decimal_interest_' + ownerId + '">Decimal Interest: </label>' +
                        '<span id="decimal_interest_' + ownerId + '"></span>' +
                        '</div>' +
                        '<div class="col-md-4">' +
                        '<label class="addit_labels" style="margin-left:-15%;" for="interest_type_' + ownerId + '">Interest Type: </label>' +
                        '<span id="interest_type_' + ownerId + '"></span>' +
                        '</div></div>' +
                        '<div class="form-group form-inline">' +
                        '<label class="addit_labels" for="interest_type_' + ownerId + '">Monthly Revenue: </label>' +
                        '<input type="text" style="margin-left:8.5%;" class="form-control monthly_revenue" id="monthly_revenue_' + ownerId + '" disabled />' +
                        '</div>' +
                        '<div class="form-group form-inline">' +
                        '<label class="addit_labels" for="owner_price_' + ownerId + '">Pricing per NRA: </label>' +
                        '<input type="text" style="margin-left:10%;" class="form-control owner_price" name="owner_price" id="owner_price_' + ownerId + '" placeholder="$" />' +
                        '</div>' +
                        '<div class="form-group form-inline">' +
                        '<label class="addit_labels" for="net_royalty_acres_' + ownerId + '">Net Royalty Acres: </label>' +
                        '<input type="text" style="margin-left:7.5%;" class="form-control net_royalty_acres" disabled id="net_royalty_acres_' + ownerId + '" />' +
                        '</div>' +
                        '<div class="form-group form-inline">' +
                        '<label class="addit_labels" for="total_price_for_interest_' + ownerId + '">Total Price For Interest: </label>' +
                        '<input type="text" style="margin-left:2%;" class="form-control total_price_for_interest" disabled id="total_price_for_interest_' + ownerId + '" />' +
                        '</div>' +
                        '<div class="form-group form-inline">' +
                        '<label class="addit_labels" for="oil_price">Oil Price: </label>' +
                        '<input type="text" style="margin-left:18.5%;" class="form-control oil_price" disabled />' +
                        '</div>' +
                        '<div class="form-group form-inline">' +
                        '<label class="addit_labels" for="gas_price">Gas Price: </label>' +
                        '<input type="text" style="margin-left:17%;" class="form-control gas_price" disabled />' +
                        '</div>' +
                        '<div class="form-group form-inline">' +
                        '<label class="addit_labels" for="bnp_' + ownerId + '">BNP: </label>' +
                        '<input type="text" style="margin-left:22.8%;" class="form-control bnp" disabled id="bnp_' + ownerId + '" />' +
                        '</div>' +
                        '<div class="form-group form-inline">' +
                        '<label class="addit_labels" for="ytp">Years to PayOff: </label>' +
                        '<input type="text" style="margin-left:10%;" class="form-control ytp" id="ytp_' + ownerId + '" disabled />' +
                        '</div>' +
                        '</div></div>' +
                        '<div class="col-md-6">' +
                        '<div style="text-align:center;" class="col-md-12">' +
                        '<label style="font-size:20px; font-weight:bold;" for="notes">Owner Notes</label>' +
                        '<div class="previous_owner_notes" id="previous_owner_notes_' + ownerId + '" name="previous_owner_notes" contenteditable="false"></div>' +
                        '</div>' +
                        '<div style="text-align:center;" class="col-md-12">' +
                        '<label style="font-size:20px; font-weight:bold;" for="owner_notes_' + ownerId + '">Enter Owner Notes</label>' +
                        '<textarea rows="4" class="owner_notes" id="owner_notes_' + ownerId + '" name="notes" style="width:100%;" placeholder="Enter Notes: "></textarea>' +
                        '<div class="col-md-12">' +
                        '<button type="button" id="update_owner_notes_btn_' + ownerId + '" class="btn btn-primary update_owner_notes_btn">Update Notes</button>' +
                        '</div></div></div>';

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(ownerBody).show();
                        tr.addClass('shown');
                    }

                    let price = 0.0;
                    if (data.price !== null) {
                        price = data.price;
                    }

                    $('#owner_price_' + ownerId).val(' $' + price);
                    $('#lease_description_' + ownerId).text(' ' + data.lease_description);
                    $('#decimal_interest_' + ownerId).text(' ' + data.owner_decimal_interest);
                    $('#interest_type_' + ownerId).text(' ' + data.owner_interest_type);

                    let monthlyRevenue = data.tax_value / 12;
                    monthlyRevenue = monthlyRevenue.toFixed(2);
                    monthlyRevenue = numberWithCommas(monthlyRevenue);
                    $('#monthly_revenue_' + ownerId).val(monthlyRevenue);

                    $('#active_well_count_' + ownerId).text(' ' + $('#well_count').val());
                    $('#first_prod_' + ownerId).text(' ' + $('#first_month').text());
                    $('#last_prod_' + ownerId).text(' ' + $('#last_month').text());
                    $('#cum_prod_oil_' + ownerId).text(' ' + $('#total_oil').text());
                    $('#cum_prod_gas_' + ownerId).text(' ' + $('#total_gas').text());
                    $('#years_of_prod_' + ownerId).text(' ' + $('#years_of_prod').text());
                    $('#bbls_' + ownerId).text(' ' + $('#bbls').text());
                    $('#gbbls_' + ownerId).text(' ' + $('#gbbls').text());

                    let ownerPrice = $('#owner_price_' + ownerId).val();
                    if (ownerPrice !== undefined) {
                        ownerPrice = ownerPrice.replace('$', '');
                    } else {
                        ownerPrice = 0;
                    }

                    let netRoyaltyAcres = data.owner_decimal_interest / .125 * $('.acreage').val();
                    netRoyaltyAcres = netRoyaltyAcres.toFixed(4);
                    $('#net_royalty_acres_' + ownerId).val(netRoyaltyAcres);

                    let total = ownerPrice * $('#net_royalty_acres_' + ownerId).val();
                    let totalPriceForInterest = total.toFixed(2);
                    let totalPriceForInterestWithCommas = numberWithCommas(totalPriceForInterest);

                    $('#total_price_for_interest_' + ownerId).val('$' + totalPriceForInterestWithCommas);

                    let neededIncome = totalPriceForInterest / data.owner_decimal_interest;
                    let bnp = neededIncome / data.oilPrice;
                    bnp = bnp.toFixed(2);
                    let bnpWithComma = numberWithCommas(bnp);

                    $('.oil_price').val(data.oilPrice);
                    $('.gas_price').val(data.gasPrice);
                    $('#bnp_' + ownerId).val(bnpWithComma);

                    let bbls = $('#bbls').text();
                    bbls = bbls.replace(',', '');

                    let ytp = bnp / bbls;

                    ytp = ytp.toFixed(2);
                    let ytpWithComma = numberWithCommas(ytp);
                    $('#ytp_' + ownerId).val(ytpWithComma);

                    getOwnerNotes(ownerId);
                },
                error: function error(data) {
                    console.log(data);
                }
            });
        });
    }
});
