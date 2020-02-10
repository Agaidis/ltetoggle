@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Mineral Management Platform - All Permits</div>
                    <div class="card-body">
                        <div class="row">
                            <div id="dashboard_btn_container" class="col-md-4">
                                <div class="button_panel">
                                    @if (Auth::user()->role === 'admin')
                                    <a href="{{ url('welbore') }}"><button type="button" class="btn btn-primary dashboard_btns" id="welbore_btn">Wellbore</button></a>
                                    <a href="{{ url('user-mmp') }}">
                                        <button style="margin-left:5%;" type="button" class="btn btn-primary dashboard_btns" id="user_mmp_btn">{{Auth::user()->name}}</button>
                                    </a>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <table class="table table-hover table-responsive-md table-bordered" id="permit_table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Assignee</th>
                                        <th class="text-center">State / County</th>
                                        <th class="text-center">Reported Operator</th>
                                        <th class="text-center">Lease Name</th>
                                        <th class="text-center">More Data</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (isset($permits) && !$permits->isEmpty())
                                    @foreach ($permits as $permit)
                                        <?php $approvedDate = explode('T', $permit->approved_date)?>

                                        <tr class="permit_row" id="permit_row_{{$permit->permit_id}}">
                                            <td class="text-center">
                                                <select class="form-control assignee" id="assignee_{{$permit->permit_id}}">
                                                    <option selected disabled>Select a User</option>
                                                    @foreach ($users as $user)
                                                        @if ($permit->assignee == $user->id)
                                                            <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                        @else
                                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">{{$permit->county_parish}}</td>
                                            <td class="text-center">{{$permit->reported_operator}}</td>
                                            <td class="text-center"><a href="{{url( 'mineral-owner/' . $permit->lease_name . '/' . $permit->reported_operator . '/' . $permit->id)}}">{{$permit->lease_name}}</a></td>
                                            <td class="text-center">
                                                <button type="button" data-target="#modal_show_permit" data-toggle="modal" id="id_{{$permit->permit_id}}_{{$permit->reported_operator}}" class="fa fa-edit btn-sm btn-primary view_permit"></button>
                                            </td>
                                        </tr>

                                    @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                    <caption class="lease_table_caption">Landtrac's Producing/Non-Producing </caption>
                                    </tfoot>
                                </table>
                            </div>
                            <div style="margin-top:1.5%;" class="col-md-4">
                                <label style="font-size:20px; font-weight:bold;" for="notes">Previous Landtrac Notes</label>
                                <div class="previous_notes" name="previous_notes" contenteditable="false"></div><br>

                                <label style="font-size:20px; font-weight:bold;" for="notes">Submit Landtrac Notes</label>
                                <textarea rows="6" class="notes" name="notes" style="width:inherit;" placeholder="Enter Notes: "></textarea>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary update_permit_notes_btn">Update Notes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal_show_permit">
                    <div class="modal-dialog" role="document">
                        <div style="width:150%; margin-left:-116px;" class="set_court_date_modal modal-content">
                            <div class="modal-header">
                                <h4>Lease Name: <span id="LeaseName"></span></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3>Location & Contact</h3>
                                        <div class="containers">
                                            <label for="permit_number">Permit Number</label>
                                            <span id="permit_number"></span><br>

                                            <label for="County/Parish">County State: </label>
                                            <span id="CountyParish"></span><br>

                                            <label for="Township">Township: </label>
                                            <span id="Township"></span><br>

                                            <label for="OperatorAlias">Operator: </label>
                                            <span id="OperatorAlias"></span><br>

                                            <label for="area_acres">Acreage: </label>
                                            <span id="area_acres"></span><br>

                                            <label for="Range">Range: </label>
                                            <span id="Range"></span><br>

                                            <label for="Section">Section: </label>
                                            <span id="Section"></span><br>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h3>Types</h3>
                                        <div class="containers">
                                            <label for="permitStatus">Permit Status: </label>
                                            <span id="permitStatus"></span><br>

                                            <label for="DrillType">Drill Type: </label>
                                            <span id="DrillType"></span><br>

                                            <label for="PermitType">Permit Type: </label>
                                            <span id="PermitType"></span><br>

                                            <label for="WellType">Well Type: </label>
                                            <span id="WellType"></span><br>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <h3>Addit. Info</h3>
                                        <div class="containers">

                                            <label for="approved_date">Approved Date: </label>
                                            <span id="ApprovedDate"></span><br>

                                            <label for="submitted_date">Submitted Date: </label>
                                            <span id="SubmittedDate"></span><br>

                                            <label for="expiration_primary_term">Expiration Primary Term: </label>
                                            <span id="expiration_primary_term"></span><br>

                                            <label for="Survey">Survey: </label>
                                            <span id="Survey"></span><br>

                                            <label for="Abstract">Abstract: </label>
                                            <span id="Abstract"></span><br>

                                            <label for="District">District: </label>
                                            <span id="District"></span><br>

                                            <label for="Block">Block: </label>
                                            <span id="Block"></span>
                                        </div>
                                    </div>
                                    <div id="map"></div>
                                </div><br>
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
@endsection
