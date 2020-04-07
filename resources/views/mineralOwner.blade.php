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
                                                <th class="text-center">More Data</th>
                                                <th class="text-center">Assignee/Follow-Up</th>
                                                <th class="text-center">Wellbore Type</th>
                                                <th class="text-center" style="width:100px;">Contact</th>
                                                <th class="text-center">Follow-Up</th>
                                                <th class="text-center">Owner</th>
                                                <th class="text-center">ODI</th>
                                                <th class="text-center">% Type</th>
                                            @else
                                                <th class="text-center">Col 1</th>
                                                <th class="text-center">Col 2</th>
                                                <th class="text-center">Col 3</th>
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
                                                    <td id="id_{{$owner->id}}" class="text-center owner-details-control view_owner"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                    <td class="text-center">
                                                        @if (Auth::user()->name === 'Billy Moreaux' && ($owner->assignee != null || $owner->assignee != 0))
                                                            <select class="form-control owner_assignee" id="assignee_{{$owner->id}}">
                                                        @elseif (($owner->assignee != null && $owner->assignee != '0'))
                                                                    <select disabled class="form-control owner_assignee" id="assignee_{{$owner->id}}">
                                                                        @else
                                                                            <select class="form-control owner_assignee" id="assignee_{{$owner->id}}">
                                                                        @endif
                                                                        <option selected value="0">Select a User</option>
                                                                        @foreach ($users as $user)
                                                                            @if ($user->id == $owner->assignee)
                                                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                                            @else
                                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                            </select>

                                                    </td>
                                                    <td class="text-center">
                                                                @if (Auth::user()->name === 'Billy Moreaux' && ($owner->wellbore_type != null || $owner->wellbore_type != 0))
                                                            <select class="form-control wellbore_dropdown" id="wellbore_dropdown_{{$owner->id}}">
                                                                @elseif (($owner->wellbore_type != null && $owner->wellbore_type != '0'))
                                                                    <select disabled class="form-control wellbore_dropdown" id="wellbore_dropdown_{{$owner->id}}">
                                                                @else
                                                                            <select class="form-control wellbore_dropdown" id="wellbore_dropdown_{{$owner->id}}">
                                                                @endif
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
                                                        <button class="btn btn-primary add_phone_btn" id="add_phone_{{$owner->id}}" data-target="#modal_add_phone" data-toggle="modal">Contact Info</button>
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
                                                    @else
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary add_phone_btn" id="add_phone_{{$owner->id}}" data-target="#modal_add_phone" data-toggle="modal">Contact Info</button>
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
                            <div style="margin-left:-150px; width:850px;" class="modal-content">
                                <div class="modal-header">
                                    <h4>Wells </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-hover table-responsive-md table-bordered wells_table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Well Prod Details</th>
                                            <th class="text-center">County</th>
                                            <th class="text-center">Operator</th>
                                            <th class="text-center">Current Status</th>
                                            <th class="text-center">Well Name</th>
                                            <th class="text-center">Well Number</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($wells as $well)
                                            <tr>
                                                <td id="{{$well->government_id}}" class="text-center details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                <td class="text-center">{{$well->county}}</td>
                                                <td class="text-center">{{$well->current_operator}}</td>
                                                <td class="text-center">{{$well->current_status}}</td>
                                                <td class="text-center">{{$well->well_name}}</td>
                                                <td class="text-center">{{$well->well_number}}</td>
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
            </div>
        </div>
    </div>
@endsection
