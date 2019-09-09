@extends('layouts.app')
@section('content')
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body body_container">
                        <h2 class="titles">FAQ Page</h2>
                        <h4>1. Where does this currently work?</h4>
                        <ul>
                            <li>Currently, Toggle is in beta and only works in parts of Texas:</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection