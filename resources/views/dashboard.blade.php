@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        <div class="row">
                            <div id="dashboard_btn_container" class="col-md-4">
                                <div class="button_panel">
                                    <a href="{{ url('welbore') }}"><button type="button" class="btn btn-primary dashboard_btns" id="welbore_btn">Wellbore</button></a>
                                    <a href="{{ url('new-permits') }}"><button type="button" class="btn btn-primary dashboard_btns" id="abstract_btn">Permits</button></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                            <table class="table table-hover table-responsive-md table-bordered" id="lease_table">
                                <thead>
                                <tr>
                                    <th class="text-center">Assignee</th>
                                    <th class="text-center">County Parish</th>
                                    <th class="text-center">Grantee</th>
                                    <th class="text-center">Grantor</th>
                                    <th class="text-center">Spatial Assignee</th>
                                    <th class="text-center">Area Acres</th>
                                    <th class="text-center">More Data</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($leases as $lease)

                                <tr class="lease_row" id="lease_row_{{$lease->lease_id}}">
                                    <td class="text-center">
                                        <select class="form-control assignee" id="assignee_{{$lease->lease_id}}">
                                            <option selected disabled>Select a User</option>
                                            @foreach ($users as $user)
                                                @if ($lease->assignee == $user->id)
                                                    <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                @else
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">{{$lease->county_parish}}</td>
                                    <td class="text-center">{{$lease->grantee}}</td>
                                    <td class="text-center">{{$lease->grantor}}</td>
                                    <td class="text-center"><a href="{{url( 'mineral-owner/' . $lease->spatial_assignee)}}">{{$lease->spatial_assignee}}</a></td>
                                    <td class="text-center">{{$lease->area_acres}}</td>
                                    <td class="text-center">
                                        <button type="button" data-target="#modal_show_lease" data-toggle="modal" id="id_{{$lease->lease_id}}" class="fa fa-edit btn-sm btn-primary view_lease"></button>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <caption id="lease_table_caption">Leases: Non-Producing & Producing</caption>
                                </tfoot>
                            </table>
                            </div>
                            <div style="margin-top:1.5%;" class="col-md-4">
                                <label style="font-size:20px; font-weight:bold;" for="notes">Lease Notes</label>
                                <textarea rows="6" class="notes" name="notes" style="width:inherit;" placeholder="Enter Notes: "></textarea>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary update_lease_notes_btn">Update Notes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal_show_lease">
                    <div class="modal-dialog" role="document">
                        <div style="width:150%; margin-left:-116px;" class="set_court_date_modal modal-content">
                            <div class="modal-header">
                                <h4>Lease Data: </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                <div class="col-md-4">
                                    <h3>Land Info.</h3>
                                    <div class="containers">
                                        <label for="area_acres">Area Acres: </label>
                                        <span id="area_acres"></span><br>

                                        <label for="county">County: </label>
                                        <span id="county"></span><br>

                                        <label for="exp_primary_term">Expiration Primary Term: </label>
                                        <span id="exp_primary_term"></span><br>
                                    </div>
                                </div>
                                    <div class="col-md-8">
                                        <h3>Personnel Info.</h3>
                                        <div class="containers">
                                        <label for="grantee">Grantee: </label>
                                        <span id="grantee"></span><br>

                                        <label for="grantee_alias">Grantee Alias: </label>
                                        <span id="grantee_alias"></span><br>

                                        <label for="grantor">Grantor: </label>
                                        <span id="grantor"></span><br>

                                        <label for="grantor_address">Grantor Address: </label>
                                        <span id="grantor_address"></span><br>
                                        </div>
                                    </div>
                                </div><br><br>
                                <div id="map"></div>
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
@endsection
