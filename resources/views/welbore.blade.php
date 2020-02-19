@extends('layouts.app')
@section('content')
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body body_container">
                        <div class="col-md-12 titles">
                            <h1>Wellbore</h1>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table class="table table-hover table-responsive-md table-bordered high_priority_wellbore_table" style="width:1475px;">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Assignee</th>
                                            <th class="text-center">Wellbore Type</th>
                                            <th class="text-center" style="width:250px;">Contact</th>
                                            <th class="text-center">Follow-Up</th>
                                            <th class="text-center">Owner</th>
                                            <th class="text-center">ODI</th>
                                            <th class="text-center">% Type</th>
                                            <th class="text-center">More Data</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($highPriorityProspects as $highPriorityProspect)
                                            @if ($highPriorityProspect->follow_up_date == date('Y-m-d') || $highPriorityProspect->follow_up_date > date('Y-m-d') || $highPriorityProspect->follow_up_date === NULL)
                                                <tr class="owner_row" id="owner_row_{{$highPriorityProspect->id}}">
                                            @else
                                                <tr class="owner_row" style="background-color: #f59278;" id="owner_row_{{$highPriorityProspect->id}}">
                                            @endif
                                                    @if (isset($highPriorityProspect->lease_name))
                                                        <input type="hidden" name="lease_name" id="lease_name_{{$highPriorityProspect->id}}" value="{{$highPriorityProspect->lease_name}}"/>
                                                    @else
                                                        <input type="hidden" name="lease_name" id="lease_name_{{$highPriorityProspect->id}}" value="{{$operator}}"/>
                                                    @endif
                                                <td class="text-center">
                                                    <select class="form-control owner_assignee" id="assignee_{{$highPriorityProspect->id}}">
                                                        <option value="0" selected >Select a User</option>
                                                        @foreach ($users as $user)
                                                            @if ($highPriorityProspect->assignee == $user->id)
                                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                            @else
                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <select class="form-control wellbore_dropdown" id="wellbore_dropdown_{{$highPriorityProspect->id}}">
                                                        @if ($highPriorityProspect->wellbore_type == 1)
                                                            <option value="0">None</option>
                                                            <option selected value="{{$highPriorityProspect->wellbore_type}}">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        @elseif ($highPriorityProspect->wellbore_type == 2)
                                                            <option value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option selected value="{{$highPriorityProspect->wellbore_type}}">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        @elseif ($highPriorityProspect->wellbore_type == 3)
                                                            <option value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option selected value="{{$highPriorityProspect->wellbore_type}}">3</option>
                                                            <option value="4">4</option>
                                                        @elseif ($highPriorityProspect->wellbore_type == 4)
                                                            <option value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option selected value="{{$highPriorityProspect->wellbore_type}}">4</option>
                                                        @else
                                                            <option selected value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary add_phone_btn" id="add_phone_{{$highPriorityProspect->owner}}" data-target="#modal_add_phone" data-toggle="modal">Contact Info</button>
                                                </td>
                                                <td class="text-center">
                                                    @if ($highPriorityProspect->follow_up_date != '')
                                                        <i class="fas fa-calendar-alt"></i> <input class="form-control owner_follow_up" id="owner_follow_up_{{$highPriorityProspect->id}}" value="{{date('M j, Y', strtotime($highPriorityProspect->follow_up_date))}}" />
                                                    @else
                                                        <i class="fas fa-calendar-alt"></i> <input class="form-control owner_follow_up" id="owner_follow_up_{{$highPriorityProspect->id}}" />
                                                    @endif
                                                </td>
                                                <td class="text-center"><a href="{{url( 'owner/' . $highPriorityProspect->owner)}}">{{$highPriorityProspect->owner}}</a><br>{{$highPriorityProspect->owner_address}}<br>{{$highPriorityProspect->owner_city}}, {{$highPriorityProspect->owner_zip}}</td>
                                                <td class="text-center">{{$highPriorityProspect->owner_decimal_interest}}</td>
                                                <td class="text-center">{{$highPriorityProspect->owner_interest_type}}</td>
                                                <td class="text-center">
                                                    <button type="button" data-target="#modal_show_owner" data-toggle="modal" id="id_{{$highPriorityProspect->id}}" class="fa fa-edit btn-sm btn-primary view_owner"></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <caption class="lease_table_caption">High Priority Prospects </caption>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>



                        <div class="lease_notes_container row">
                            <div style="text-align:center;" class="offset-3 col-md-3">
                                <label style="font-size:20px; font-weight:bold;" for="notes">Owner Notes</label>
                                <div class="previous_owner_notes" id="previous_owner_notes" name="previous_owner_notes" contenteditable="false"></div>
                            </div>
                            <div style="text-align:center;" class="col-md-3">
                                <label style="font-size:20px; font-weight:bold;" for="notes">Enter Owner Notes</label>
                                <textarea rows="4" class="owner_notes" id=owner_notes" name="notes" style="width:100%;" placeholder="Enter Notes: "></textarea>
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary update_owner_notes_wellbore_btn">Update Notes</button>
                                </div>
                            </div>
                        </div>




                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table class="table table-hover table-responsive-md table-bordered low_priority_wellbore_table" style="width:1475px;">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Assignee/Follow-Up</th>
                                            <th class="text-center">Wellbore Type</th>
                                            <th class="text-center" style="width:250px;">Contact</th>
                                            <th class="text-center">Follow-Up</th>
                                            <th class="text-center">Owner</th>
                                            <th class="text-center">ODI</th>
                                            <th class="text-center">% Type</th>
                                            <th class="text-center">More Data</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($owners as $owner)
                                            @if ($owner->follow_up_date == date('Y-m-d') || $owner->follow_up_date > date('Y-m-d') || $owner->follow_up_date === NULL)
                                                <tr class="owner_row" id="owner_row_{{$owner->id}}">
                                            @else
                                                <tr class="owner_row" style="background-color: #f59278;" id="owner_row_{{$owner->id}}">
                                            @endif
                                                    <td class="text-center">
                                                        <select class="form-control owner_assignee" id="assignee_{{$owner->id}}">
                                                            <option selected value="0">Select a User</option>
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
                                                    <select disabled class="form-control wellbore_dropdown" id="wellbore_dropdown_{{$owner->id}}">
                                                        @if ($owner->wellbore_type == 1)
                                                            <option value="0">None</option>
                                                            <option selected value="{{$owner->wellbore_type}}">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        @elseif ($owner->wellbore_type == 2)
                                                            <option value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option selected value="{{$owner->wellbore_type}}">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        @elseif ($owner->wellbore_type == 3)
                                                            <option value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option selected value="{{$owner->wellbore_type}}">3</option>
                                                            <option value="4">4</option>
                                                        @elseif ($owner->wellbore_type == 4)
                                                            <option value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option selected value="{{$owner->wellbore_type}}">4</option>
                                                        @else
                                                            <option selected value="0">None</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary add_phone_btn" id="add_phone_{{$owner->owner}}" data-target="#modal_add_phone" data-toggle="modal">Contact Info</button>
                                                </td>
                                                <td class="text-center">
                                                    @if ($owner->follow_up_date != '')
                                                        <i class="fas fa-calendar-alt"></i> <input class="form-control owner_follow_up" id="owner_follow_up_{{$owner->id}}" value="{{date('M j, Y', strtotime($owner->follow_up_date))}}" />
                                                    @else
                                                        <i class="fas fa-calendar-alt"></i> <input class="form-control owner_follow_up" id="owner_follow_up_{{$owner->id}}" />
                                                    @endif
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
                                        <caption class="lease_table_caption">Prospects</caption>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
















                        <div class="modal fade" id="modal_add_phone">
                            <div style="width:650px!important;" class="modal-dialog phone_modal_dialog" role="document">
                                <div style="margin-left:-60%; margin-top:50%;" class="modal-content">
                                    <div class="modal-header">
                                        <h4>Add Phone Number</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body row">
                                        <div class="col-md-6">
                                            <label>Phone Description</label><input type="text" class="form-control" id="new_phone_desc" name="new_phone_desc" placeholder="Home, Cell, Sister, Etc."/>
                                            <label>Phone Number</label><input type="text" class="form-control" id="new_phone_number" name="new_phone_number" placeholder="(ext) 000 - 0000"/>
                                            <div class="modal-footer">
                                                <button type="button" id="submit_phone" class="submit_phone_btn btn btn-primary" >Submit #</button>
                                                <button type="button" id="cancel_phone" class="cancel_phone_btn btn btn-success" data-dismiss="modal" >Close Modal</button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="phone_container" id="phone_container" style="padding: 2%;"></span>
                                        </div>
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

    </div>
@endsection