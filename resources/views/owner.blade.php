@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Owners Page</div>
                    <div class="card-body">
                        <h2 style="text-align:center;">Owner Name: {{$ownerName}}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
