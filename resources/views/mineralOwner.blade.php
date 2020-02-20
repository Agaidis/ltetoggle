@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Lease Information & Mineral Owners</div>
                    <div class="card-body">
                        <div class="row">
                            <div id="dashboard_btn_container" class="col-md-4">
                                @if (Auth::user()->role === 'admin')
                                <div class="button_panel">
                                    <a href="{{ url('welbore') }}">
                                        <button type="button" class="btn btn-primary dashboard_btns" id="welbore_btn">Wellbore</button>
                                    </a>
                                    <a href="{{ url('user-mmp') }}">
                                        <button type="button" style="margin-left:5%;" class="btn btn-primary dashboard_btns" id="user_mmp_btn">{{Auth::user()->name}}</button>
                                    </a>
                                    <button type="button" style="margin-left:5%;" class="btn btn-primary dashboard_btns" data-target="#modal_open_wells" data-toggle="modal" id="well_count_btn">Well Count: {{$count}}</button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <span style="font-size:20px;"><b>Lease Name</b>: {{$leaseName}} <span id="acreage_container"><label>Acreage : </label> <input type="text" placeholder="Acreage" class="acreage" id="acreage_{{$permitValues->id}}" name="acreage" value="{{$permitValues->acreage}}"/></span></span>
                                <br><span style="font-size:20px;">Operator Name: {{$permitValues->reported_operator}}</span>
                            </div>
                        </div>
                            <div style="margin-top:1.5%;" class="offset-3 col-md-9">
                                <div class="row">
                                    <div style="text-align:center;" class="col-md-4">
                                        <label style="font-size:20px; font-weight:bold;" for="notes">Lease Notes</label>
                                        <div class="previous_notes" id="previous_notes" name="previous_notes" contenteditable="false"></div>
                                        <?php $notes = '';?>
                                        @foreach ($permitNotes as $permitNote)
                                            <?php $notes .= $permitNote->notes ?>
                                        @endforeach

                                        <input type="hidden" id="hidden_permit_notes" value="{{$notes}}" />
                                    </div>
                                    <div class="col-md-2">
                                        <label for="county">County: </label>
                                        <span id="county">{{$permitValues->county_parish}}</span><br>

                                        @if ($permitValues->range != '')
                                            <label for="Range">Range: </label>
                                            <span id="Range">{{$permitValues->range}}</span><br>
                                        @endif

                                        @if ($permitValues->section != '')
                                            <label for="Section">Section: </label>
                                            <span id="Section">{{$permitValues->section}}</span><br>
                                        @endif

                                        @if ($permitValues->drill_type != '')
                                        <label for="DrillType">Drill Type: </label>
                                        <span id="DrillType">{{$permitValues->drill_type}}</span><br>
                                        @endif

                                        @if ($permitValues->permit_type != '')
                                        <label for="PermitType">Permit Type: </label>
                                        <span id="PermitType">{{$permitValues->permit_type}}</span><br>
                                        @endif

                                        @if ($permitValues->well_type != '')
                                        <label for="WellType">Well Type: </label>
                                        <span id="WellType">{{$permitValues->well_type}}</span><br>
                                        @endif

                                        @if ($permitValues->block != '')
                                        <label for="Block">Block: </label>
                                        <span id="Block">{{$permitValues->block}}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        @if ($permitValues->approved_date != '')
                                            <label for="approved_date">Approved Date: </label>
                                            <span id="ApprovedDate">{{$permitValues->approved_date}}</span><br>
                                        @endif

                                        @if ($permitValues->submitted_date != '')
                                            <label for="submitted_date">Submitted Date: </label>
                                            <span id="SubmittedDate">{{$permitValues->submitted_date}}</span><br>
                                        @endif

                                            @if ($permitValues->expiration_primary_term != '')
                                                <label for="expiration_primary_term">Expiration Primary Term: </label>
                                                <span id="expiration_primary_term">{{$permitValues->expiration_primary_term}}</span><br>
                                            @endif

                                            @if ($permitValues->survey != '')
                                                <label for="Survey">Survey: </label>
                                                <span id="Survey">{{$permitValues->survey}}</span><br>
                                            @endif


                                            @if ($permitValues->abstract != '')
                                                <label for="Abstract">Abstract: </label>
                                                <span id="Abstract">{{$permitValues->abstract}}</span><br>
                                            @endif

                                            @if ($permitValues->district != '')
                                                <label for="District">District: </label>
                                                <span id="District">{{$permitValues->district}}</span>
                                            @endif
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
                                            <button type="button" class="btn btn-primary update_owner_notes_btn">Update Notes</button>
                                        </div>
                                    </div>

                                </div>
                        @endif
                            </div>
                            @if (isset($owners[0]->lease_name))
                                <input type="hidden" name="lease_name" id="lease_name" value="{{$owners[0]->lease_name}}"/>
                            @else
                                <input type="hidden" name="lease_name" id="lease_name" value="{{$operator}}"/>
                            @endif

                                <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table class="table table-hover table-responsive-md table-bordered owner_table" style="width:1475px;">
                                        <thead>

                                        <tr>
                                            @if (Auth::user()->role === 'admin')
                                                <th class="text-center">Assignee/Follow-Up</th>
                                                <th class="text-center">Wellbore Type</th>
                                                <th class="text-center" style="width:100px;">Contact</th>
                                                <th class="text-center">Follow-Up</th>
                                                <th class="text-center">Owner</th>
                                                <th class="text-center">ODI</th>
                                                <th class="text-center">% Type</th>
                                                <th class="text-center">More Data</th>
                                            @else
                                                <th class="text-center">Col 1</th>
                                                <th class="text-center">Col 2</th>
                                                <th class="text-center" style="width:100px;">Contact</th>
                                                <th class="text-center">Col 4</th>
                                                <th class="text-center">Owner</th>
                                                <th class="text-center">ODI</th>
                                                <th class="text-center">Col 7</th>
                                                <th class="text-center">Col 8</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($owners as $owner)
                                            <tr class="owner_row" id="owner_row_{{$owner->id}}">
                                                @if (Auth::user()->role === 'admin')
                                                    <td class="text-center">
                                                        @if ($owner->assignee == '' || $owner->assignee == '0')
                                                        <select class="form-control owner_assignee" id="assignee_{{$owner->id}}">
                                                            <option selected value="0">Select a User</option>
                                                            @foreach ($users as $user)
                                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @else
                                                            <input type="text" class="form-control" disabled value="Assigned" />
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($owner->wellbore_type == '' || $owner->wellbore_type == '0')
                                                        <select class="form-control wellbore_dropdown" id="wellbore_dropdown_{{$owner->id}}">

                                                                <option value="0">None</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                        </select>
                                                        @else
                                                            <input type="text" class="form-control" disabled value="Wellbore Selected"/>
                                                        @endif
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
                                                    @else
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary add_phone_btn" id="add_phone_{{$owner->owner}}" data-target="#modal_add_phone" data-toggle="modal">Contact Info</button>
                                                    </td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"><a href="{{url( 'owner/' . $owner->owner)}}">{{$owner->owner}}</a><br>{{$owner->owner_address}}<br>{{$owner->owner_city}}, {{$owner->owner_zip}}</td>
                                                    <td class="text-center">{{$owner->owner_decimal_interest}}</td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                @endif
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

                    <div class="modal fade" id="modal_open_wells">
                        <div class="modal-dialog" role="document">
                            <div style="margin-left:40%; margin-top:30%;" class="modal-content">
                                <div class="modal-header">
                                    <h4>Wells </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                        <table class="table table-hover table-responsive-md table-bordered">
                                            <thead>
                                            <tr>
                                                <th class="text-center">UID</th>
                                                <th class="text-center">Current Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($wells as $well)
                                                <tr class="owner_row">
                                                    <td class="text-center">{{$well->uid}}</td>
                                                    <td class="text-center">{{$well->current_status}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                </div>
                                <br>
                                <div class="modal-footer">
                                    <button type="button" id="cancel_phone" class="cancel_phone_btn btn btn-success" data-dismiss="modal" >Close Modal</button>
                                </div>
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
                                    <div style="margin-bottom:3%;" class="row">
                                        <div class="col-md-6">
                                            <h3 style="text-align: center;">Owner Contact</h3>
                                            <div class="containers">
                                                <div style="text-align: center; font-size:16px;" id="name_address_container">
                                                    <label for="name_address">Name & Address: </label><br>
                                                    <span id="name_address"></span><br>
                                                </div><br>
                                            </div>
                                        </div>

                                    <div class="col-md-6">
                                        <h3 style="text-align: center;">Lease Info</h3>
                                        <div class="containers">
                                            <label for="lease_name">Lease Name: </label>
                                            <span id="lease_name_display"></span><br>

                                            <label for="lease_description">Lease Description: </label>
                                            <span id="lease_description"></span><br><br>

                                            <label for="rrc_lease_number">RRC Lease Number: </label>
                                            <span id="rrc_lease_number"></span><br>
                                        </div>
                                    </div>
                                    </div>
                                    <div style="margin-bottom:4%;" class="col-md-12">
                                            <h3 style="text-align: center;">Mineral Interest & Pricing Info.  </h3>
                                            <div class="containers">
                                                <div class="row">
                                                    <div class="offset-2 col-md-5">
                                                        <label class="addit_labels" for="decimal_interest">Decimal Interest: </label>
                                                        <span id="decimal_interest"></span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="addit_labels" style="margin-left:-15%;" for="interest_type">Interest Type: </label>
                                                        <span id="interest_type"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group form-inline">
                                                    <label class="addit_labels control-label" for="owner_price">Pricing per NRA: </label>
                                                    <input type="text" style="margin-left:12%;" class="form-control owner_price" name="owner_price" id="owner_price" />
                                                </div>

                                                <div class="form-group form-inline">
                                                    <label class="addit_labels" for="net_royalty_acres">Net Royalty Acres: </label>
                                                    <input type="text" style="margin-left:7.5%;" class="form-control" disabled id="net_royalty_acres" />
                                                </div>

                                                <div class="form-group form-inline">
                                                    <label class="addit_labels" for="total_price_for_interest">Total Price For Interest: </label>
                                                    <input type="text" style="margin-left:2%;" class="form-control" disabled id="total_price_for_interest" />
                                                </div>
                                            </div>
                                        </div>
                                    <div class="col-md-12">
                                        <h3 style="text-align: center;">Additional Info</h3>
                                        <div class="containers">

                                            <label class="addit_labels" for="tax_value">Tax Value: </label>
                                            <span id="tax_value"></span><br>

                                            <label class="addit_labels" for="first_prod">First Prod Date: </label>
                                            <span id="first_prod"></span><br>

                                            <label class="addit_labels" for="last_prod">Last Prod Date: </label>
                                            <span id="last_prod"></span><br>

                                            <label class="addit_labels" for="cum_prod_oil">Cumulative Prod Oil: </label>
                                            <span id="cum_prod_oil"></span><br>

                                            <label class="addit_labels" for="cum_prod_gas">Cumulative Prod Gas: </label>
                                            <span id="cum_prod_gas"></span><br>

                                            <label class="addit_labels" for="active_well_count">Active Well Count: </label>
                                            <span id="active_well_count"></span><br>
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
