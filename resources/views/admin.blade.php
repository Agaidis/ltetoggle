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
                                <h3>Grab/Update Permits by County</h3>
                                <div class="col-md-6">
                                    <select class="form-control county_parish" id="county_select">
                                        <option value="none" selected disabled>Select a County/Parish</option>
                                        <option value="KARNES">KARNES (TX)</option>
                                        <option value="DEWITT">DeWitt (TX)</option>
                                        <option value="GONZALES">Gonzales (TX)</option>
                                        <option values="LIVE OAK">Live Oak</option>
                                        <option value="LAVACA">Lavaca</option>
                                    </select>
                                </div><br>
                                <div class="col-md-4">
                                    <button type="button" id="update_permit_btn" class="btn btn-primary">Update Database</button>
                                    <span class="loader"></span>
                                </div><br>
                            </div><br>
                            <div style="text-align:center;" class="col-md-offset-2 col-md-8">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif
                                @if(Session::has('alert-danger'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('alert-danger') }}</p>
                                @endif
                                <div class="messages"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
