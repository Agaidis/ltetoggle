@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Mineral Owner and associated Permits</div>
                    <div class="card-body">
                        <h2 style="text-align:center;">Lease Name: {{$owners[0]->lease_name}}</h2>
                        <h3 style="text-align:center;">Operator Name: {{$owners[0]->operator_company_name}}</h3>

                        <div class="row">
                            <div class="col-md-12">
                                    <table class="table table-hover table-responsive-md table-bordered" id="lease_table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Owner</th>
                                            <th class="text-center">Owner Decimal Interest</th>
                                            <th class="text-center">Interest Type</th>
                                            <th class="text-center">RRC Lease Number</th>
                                            <th class="text-center">First Prod Date</th>
                                            <th class="text-center">Last Prod Date</th>
                                            <th class="text-center">Cum Prod Oil</th>
                                            <th class="text-center">Active Well Count</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($owners as $owner)
                                            <tr class="lease_row" id="lease_row_{{$owner->id}}">
                                                <td class="text-center">{{$owner->owner}}<br>{{$owner->owner_address}}<br>{{$owner->owner_city}}, {{$owner->owner_zip}}</td>
                                                <td class="text-center">{{$owner->owner_decimal_interest}}</td>
                                                <td class="text-center">{{$owner->owner_interest_type}}</td>
                                                <td class="text-center">{{$owner->rrc_lease_number}}</td>
                                                <td class="text-center">{{$owner->first_prod_date}}</td>
                                                <td class="text-center">{{$owner->last_prod_date}}</td>
                                                <td class="text-center">{{$owner->cum_prod_oil}}</td>
                                                <td class="text-center">{{$owner->active_well_count}}</td>
                                                <td class="text-center">
                                                    <button type="button" data-target="#modal_show_owner" data-toggle="modal" id="id_{{$owner->id}}" class="fa fa-edit btn-sm btn-primary view_owner"></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <caption id="lease_table_caption">Owners</caption>
                                        </tfoot>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
