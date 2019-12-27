@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Mineral Owner and associated Permits</div>
                    <div class="card-body">
                        @if (isset($owners[0]->lease_name))
                        <h2 style="text-align:center;">Lease Name: {{$owners[0]->lease_name}}</h2>
                        <h3 style="text-align:center;">Operator Name: {{$owners[0]->operator_company_name}}</h3>
                        @else
                            <h2 style="text-align:center;">Lease Name: {{$operator}}</h2>
                            <h3 style="text-align:center;">Operator Name: {{$reporter}}</h3>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table class="table table-hover table-responsive-md table-bordered" id="owner_table" style="width:1475px;">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Assignee</th>
                                            <th class="text-center">Contact</th>
                                            <th class="text-center">Owner</th>
                                            <th class="text-center">Owner Decimal Interest</th>
                                            <th class="text-center">Interest Type</th>
                                            <th class="text-center">More Data</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($owners as $owner)
                                            <tr class="owner_row" id="owner_row_{{$owner->id}}">
                                                <td class="text-center">
                                                    <select class="form-control owner_assignee" id="assignee_{{$owner->id}}">
                                                        <option selected disabled>Select a User</option>
                                                        @foreach ($users as $user)
                                                            @if ($owner->assignee == $user->id)
                                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                            @else
                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">

                                                    <label style="float:left; margin-left:-8px; margin-top:5px;" for="cell_" id="cell">Cell: </label><input style="margin-left:14px;" type="text" id="cell_{{$owner->id}}" class="cell" value="{{$owner->cell}}"/><br>
                                                    <label style="float:left; margin-left:-27px; margin-top:8px;" for="work_" id="work">Work: </label><input style="margin-left:31px;margin-top:5px;" type="text" id="work_{{$owner->id}}" class="work" value="{{$owner->work}}"/>
                                                    <button style="float:right;color:limegreen;" id="update_phone_numbers_{{$owner->id}}" type="button" class="fa fa-save update_phone_numbers" data-toggle="tooltip" title="Update Phone Numbers"></button><br>
                                                    <label style="float:left; margin-left:-39px; margin-top:5px;" for="home_" id="home">Home: </label><input style="margin-top:5px;" type="text" id="home_{{$owner->id}}" class="home" value="{{$owner->home}}"/>
                                                </td>
                                                <td class="text-center">{{$owner->owner}}<br>{{$owner->owner_address}}<br>{{$owner->owner_city}}, {{$owner->owner_zip}}</td>
                                                <td class="text-center">{{$owner->owner_decimal_interest}}</td>
                                                <td class="text-center">{{$owner->owner_interest_type}}</td>
                                                <td class="text-center">
                                                    <button type="button" data-target="#modal_show_owner" data-toggle="modal" id="id_{{$owner->id}}" class="fa fa-edit btn-sm btn-primary view_owner"></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <caption id="owner_table_caption">Owners</caption>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_show_owner">
                        <div class="modal-dialog" role="document">
                            <div style="width:150%; margin-left:-116px;" class="modal-content">
                                <div class="modal-header">
                                    <h4>Owner Name: <span id="owner_name"></span></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h3 style="text-align: center;">Owner Contact</h3>
                                            <div class="containers">
                                                <div style="text-align: center; font-size:16px;" id="name_address_container">
                                                    <label for="name_address">Name & Address: </label><br>
                                                    <span id="name_address"></span><br>
                                                </div><br>
                                            </div>
                                        </div>

                                    <div class="col-md-8">
                                        <h3 style="text-align: center;">Lease Info</h3>
                                        <div style="text-align: center;" class="containers">
                                            <label for="lease_name">Lease Name: </label>
                                            <span id="lease_name"></span><br>

                                            <label for="lease_description">Lease Description: </label>
                                            <span id="lease_description"></span><br><br>

                                            <label for="rrc_lease_number">RRC Lease Number: </label>
                                            <span id="rrc_lease_number"></span><br>
                                        </div>
                                    </div>
                                    </div>
                                        <div class="col-md-12">
                                            <h3 style="text-align: center;">Additional Info</h3>
                                            <div class="containers">
                                                <label for="decimal_interest">Decimal Interest: </label>
                                                <span id="decimal_interest"></span><br>

                                                <label for="interest_type">Interest Type: </label>
                                                <span id="interest_type"></span><br>

                                                <label for="tax_value">Tax Value: </label>
                                                <span id="tax_value"></span><br>

                                                <label for="first_prod">First Prod Date: </label>
                                                <span id="first_prod"></span><br>

                                                <label for="last_prod">Last Prod Date: </label>
                                                <span id="last_prod"></span><br>

                                                <label for="cum_prod_oil">Cumulative Prod Oil: </label>
                                                <span id="cum_prod_oil"></span><br>

                                                <label for="active_well_count">Active Well Count: </label>
                                                <span id="active_well_count"></span><br>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="modal-footer">
                                        <button type="button" id="cancel_date" class="approve-btn btn btn-primary" data-dismiss="modal" >Exit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
