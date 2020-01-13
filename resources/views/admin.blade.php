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
                            <div class="col-md-6" style="margin-top: 2%; display: block;">
                                <h3>Upload permits by Date Range</h3>
                                <div class="col-md-6">
                                    <select class="form-control county_parish" id="county_select">
                                        <option selected disabled>Select a County/Parish</option>
                                        <option value="karnes_tx">KARNES (TX)</option>
                                    </select>
                                </div><br>
                                <div class="col-md-6">
                                    <label for="from">From</label>
                                    <input type="text" id="from" name="from">
                                    <label for="to">To</label>
                                    <input type="text" id="to" name="to">
                                </div><br>
                                <div class="col-md-4">
                                    <button type="button" id="update_permit_btn" class="btn btn-primary">Update Database</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
