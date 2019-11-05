@extends('layouts.app')
@section('content')
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body body_container">
                        <div id="dashboard_btn_container" class="col-md-4">
                            <div class="button_panel">
                                <a href="{{ url('welbore') }}"><button type="button" class="btn btn-primary dashboard_btns" id="welbore_btn">Wellbore</button></a>
                                <a href="{{ url('dashboard') }}"><button type="button" class="btn btn-primary dashboard_btns" id="abstract_btn">Landtrac Leases</button></a>
                            </div>
                        </div>
                        <h2 class="titles">Permits</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-hover table-responsive-md table-bordered" id="permit_table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Assignee</th>
                                        <th class="text-center">Approved Date</th>
                                        <th class="text-center">Drill Type</th>
                                        <th class="text-center">More Data</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($permits as $permit)
                                        <?php $approvedDate = explode('T', $permit->approved_date)?>
                                        @if (($permit->drill_type == 'H' || $permit->drill_type == 'V') && ($permit->well_type == 'GAS' || $permit->well_type == 'OIL'))
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
                                                <td class="text-center">{{$approvedDate[0]}}</td>
                                                <td class="text-center">{{$permit->drill_type}}</td>
                                                <td class="text-center">
                                                    <button type="button" data-target="#modal_show_permit" data-toggle="modal" id="id_{{$permit->permit_id}}" class="fa fa-edit btn-sm btn-primary view_permit"></button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <caption id="lease_table_caption">Permits</caption>
                                    </tfoot>
                                </table>
                            </div>
                            <div style="margin-top:1.5%;" class="col-md-4">
                                    <label style="font-size:20px; font-weight:bold;" for="notes">Lease Notes</label>
                                    <textarea rows="6" class="notes" name="notes" style="width:inherit;" placeholder="Enter Notes: "></textarea>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary update_permit_notes_btn">Update Notes</button>
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

                                                    <label for="County/Parish">County State: </label>
                                                    <span id="CountyParish"></span><br>

                                                    <label for="Township">Township</label>
                                                    <span id="Township"></span><br>

                                                    <label for="OperatorAlias">Operator</label>
                                                    <span id="OperatorAlias"></span><br>

                                                    <label for="area_acres">Acreage</label>
                                                    <span id="area_acres"></span><br>

                                                    <label for="Range">Range</label>
                                                    <span id="Range"></span><br>

                                                    <label for="Section">Section</label>
                                                    <span id="Section"></span><br>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h3>Types</h3>
                                                <div class="containers">
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

                                                    <label for="approved_date">Approved Date</label>
                                                    <span id="ApprovedDate"></span><br>

                                                    <label for="expiration_primary_term">Expiration Primary Term</label>
                                                    <span id="expiration_primary_term"></span><br>

                                                    <label for="Survey">Survey</label>
                                                    <span id="Survey"></span><br>

                                                    <label for="Abstract">Abstract</label>
                                                    <span id="Abstract"></span><br>

                                                    <label for="Block">Block</label>
                                                    <span id="Block"></span>
                                                </div>
                                            </div>
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

    </div>
@endsection