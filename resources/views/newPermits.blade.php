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
                                        <?php $approvedDate = explode('T', $permit->ApprovedDate)?>
                                        @if (($permit->drill_type == 'H' || $permit->drill_type == 'V') && ($permit->well_type == 'GAS' || $permit->well_type == 'OIL'))
                                            <tr class="permit_row" id="permit_row_{{$permit->permit_id}}">
                                                <td class="text-center">
                                                    <select class="form-control assignee" id="assignee_{{$permit->permit_id}}">
                                                        <option selected disabled>Select a User</option>
                                                        @foreach ($users as $user)
                                                            @if ($permit->assignee != '')
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
                                        <h4>Permit Data: </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                                <table class="table table-bordered table-hover table-dark">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Value</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td class="text-center">Abstract</td>
                                                        <td class="text-center" id="Abstract"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">ApprovedDate</td>
                                                        <td class="text-center" id="ApprovedDate"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Block</td>
                                                        <td class="text-center" id="Block"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">CountyParish</td>
                                                        <td class="text-center" id="CountyParish"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">DrillType</td>
                                                        <td class="text-center" id="DrillType"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">LeaseName</td>
                                                        <td class="text-center" id="LeaseName"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">OperatorAlias</td>
                                                        <td class="text-center" id="OperatorAlias"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">PermitType</td>
                                                        <td class="text-center" id="PermitType"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Range</td>
                                                        <td class="text-center" id="Range"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Section</td>
                                                        <td class="text-center" id="Section"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">StateProvince</td>
                                                        <td class="text-center" id="StateProvince"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Survey</td>
                                                        <td class="text-center" id="Survey"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Township</td>
                                                        <td class="text-center" id="Township"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">WellType</td>
                                                        <td class="text-center" id="WellType"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
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