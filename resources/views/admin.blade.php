@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Admin Area</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6" id="list_detail_ctr" style="margin-top: 2%; display: block;">
                                <h3>Permit Lookup: </h3>
                                <div class="row form-group">
                                    <label class="col-form-label col-sm-3" for="permit_id">Permit Id: </label>
                                    <div class="form-inline col-md-9">
                                        <input class="form-control" type="text" id="permit_id" placeholder="0239548">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="col-form-label col-sm-3" for="abstract">Abstract: </label>
                                    <div class="col-md-4">
                                        <input class="form-control list_field" id="abstract"/>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="col-form-label col-sm-3" for="approved_date">Approved Date: </label>
                                    <div class="col-md-4">
                                        <input type="date" class="form-control list_field" id="approved_date"/>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="col-form-label col-sm-3" for="block">Block: </label>
                                    <div class="col-md-6">
                                        <input class="form-control list_field" id="block" placeholder="Block">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="col-form-label col-sm-3" for="county">County: </label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="county">
                                            <option value="Karnes">Karnes</option>
                                        </select>

                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="col-form-label col-sm-3" for="drill_type">Drill Type: </label>
                                    <div class="col-md-6">
                                        <input class="form-control list_field" id="drill_type" placeholder="Drill Type">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="col-form-label col-sm-3" for="lease_name">Lease Name: </label>
                                    <div class="col-md-6">
                                        <input class="form-control list_field" id="lease_name" placeholder="Lease Name">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="col-form-label col-sm-3" for="range">Range: </label>
                                    <div class="col-md-6">
                                        <input class="form-control list_field" id="range" placeholder="Range">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="col-form-label col-sm-3" for="section">Section: </label>
                                    <div class="col-md-6">
                                        <input class="form-control list_field" id="section" placeholder="Section">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-2 create_list_ctr" style="margin-left:25%;">
                                        <span style="color:red;" id="create_message"></span>
                                        <button type="button" id="create_list_btn" class="form-control btn btn-success">Look up Permits</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
