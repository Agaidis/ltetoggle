@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Lease Information & Mineral Owners</div>
                    <div class="card-body">
                        @if (isset($owners[0]->lease_name))
                        <h2 style="text-align:center;">Lease Name: {{$owners[0]->lease_name}}</h2>
                        <h3 style="text-align:center;">Operator Name: {{$owners[0]->operator_company_name}}</h3>
                        @else
                            <h2 style="text-align:center;">Lease Name: {{$operator}}</h2>
                            <h3 style="text-align:center;">Operator Name: {{$reporter}}</h3>
                        @endif
                            <div style="margin-top:1.5%; margin-left:35%;" class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label style="font-size:20px; font-weight:bold;" for="notes">Previous Lease Owner Notes</label>
                                        <div class="previous_owner_notes" name="previous_owner_notes" contenteditable="false"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="font-size:20px; font-weight:bold;" for="notes">Enter Lease Owner Notes</label>
                                        <textarea rows="6" class="owner_notes" name="notes" style="width:100%;" placeholder="Enter Notes: "></textarea>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary update_owner_notes_btn">Update Notes</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            @if (isset($owners[0]->lease_name))
                                <input type="hidden" name="lease_name" id="lease_name" value="{{$owners[0]->lease_name}}"/>
                            @else
                                <input type="hidden" name="lease_name" id="lease_name" value="{{$operator}}"/>
                            @endif

                                <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table class="table table-hover table-responsive-md table-bordered" id="owner_table" style="width:1475px;">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Assignee</th>
                                            <th class="text-center">Wellbore Type</th>
                                            <th class="text-center" style="width:250px;">Contact</th>
                                            <th class="text-center">Owner</th>
                                            <th class="text-center">ODI</th>
                                            <th class="text-center">% Type</th>
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
                                                    <select class="form-control wellbore_dropdown" id="wellbore_dropdown_{{$owner->id}}">
                                                        @if ($owner->wellbore_type == 1)
                                                            <option disabled>Wellbore #</option>
                                                            <option selected value="{{$owner->wellbore_type}}">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        @elseif ($owner->wellbore_type == 2)
                                                            <option disabled>Wellbore #</option>
                                                            <option value="1">1</option>
                                                            <option selected value="{{$owner->wellbore_type}}">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        @elseif ($owner->wellbore_type == 3)
                                                            <option disabled>Wellbore #</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option selected value="{{$owner->wellbore_type}}">3</option>
                                                            <option value="4">4</option>
                                                        @elseif ($owner->wellbore_type == 4)
                                                            <option disabled>Wellbore #</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option selected value="{{$owner->wellbore_type}}">4</option>
                                                        @else
                                                            <option selected disabled>Wellbore #</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fas fa-plus add_phone_btn" id="add_phone_{{$owner->owner}}" data-target="#modal_add_phone" data-toggle="modal" style="color:green; cursor:pointer; float:left; text-align: left;"></span>
                                                    <span class="phone_container" style="padding: 2%;">
                                                    @for ($i = 0; $i < count($ownerPhoneNumbers); $i++)
                                                        @if ($ownerPhoneNumbers[$i]->owner === $owner->owner && $ownerPhoneNumbers[$i]->soft_delete === 0)

                                                        <div id="phone_{{$i}}" style="padding: 2%;">
                                                            <input type="hidden" id="phone_owner_{{$i}}" value="{{$ownerPhoneNumbers[$i]->owner}}"/>
                                                            <input type="hidden" id="phone_number_{{$i}}" value="{{$ownerPhoneNumbers[$i]->phone_number}}" />
                                                            <input type="hidden" id="phone_desc_{{$i}}" value="{{$ownerPhoneNumbers[$i]->phone_desc}}"/>
                                                            <span style="font-weight: bold;">{{$ownerPhoneNumbers[$i]->phone_desc}}: </span>
                                                            <span><a href="tel:{{$ownerPhoneNumbers[$i]->phone_number}}">{{$ownerPhoneNumbers[$i]->phone_number}}</a></span>
                                                            <span style="cursor:pointer; color:red; margin-left:5%;" class="soft_delete_phone fas fa-trash" id="soft_delete_{{$i}}"></span>
                                                        </div>
                                                        @endif
                                                    @endfor
                                                    </span>

                                                </td>
                                                <td class="text-center"><a href="{{url( 'owner/' . $owner->owner)}}">{{$owner->owner}}</a><br>{{$owner->owner_address}}<br>{{$owner->owner_city}}, {{$owner->owner_zip}}</td>
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

                    <div class="modal fade" id="modal_add_phone">
                        <div class="modal-dialog" role="document">
                            <div style="width:150%; margin-left:-116px;" class="modal-content">
                                <div class="modal-header">
                                    <h4>Add Phone Number</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <label>Phone Description</label><input type="text" class="form-control" id="new_phone_desc" name="new_phone_desc" placeholder="Home, Cell, Sister, Etc."/>
                                    <label>Phone Number</label><input type="text" class="form-control" id="new_phone_number" name="new_phone_number" placeholder="(ext) 000 - 0000"/>
                                </div>
                                <br>
                                <div class="modal-footer">
                                    <button type="button" id="submit_phone" class="submit_phone_btn btn btn-primary" data-dismiss="modal" >Submit Phone #</button>
                                    <button type="button" id="cancel_phone" class="cancel_phone_btn btn btn-success" data-dismiss="modal" >Cancel</button>
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

                                                <label for="cum_prod_gas">Cumulative Prod Gas: </label>
                                                <span id="cum_prod_gas"></span><br>

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
