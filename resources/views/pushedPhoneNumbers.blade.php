@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Phone Numbers to Update</div>
                    <div class="card-body">
                        <div class="row">

                                <div class="offset-1 col-md-10">
                                    <table class="table table-hover table-responsive-md table-bordered" id="phone_numbers_table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Id</th>
                                            <th class="text-center">Owner Name</th>
                                            <th class="text-center">Phone Number</th>
                                            <th class="text-center">Description</th>
                                            <th class="text-center">Send Back</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($phoneNumbers as $phoneNumber)
                                            <tr class="phone_number_row" id="phone_number_row_{{$phoneNumber->id}}">
                                                <td class="text-center">{{$phoneNumber->id}}</td>
                                                <td class="text-center">{{$phoneNumber->owner_name}}</td>
                                                <td class="text-center"><input type="text" class="form-control" id="phone_number_{{$phoneNumber->id}}" value="{{$phoneNumber->phone_number}}"/></td>
                                                <td class="text-center"><input type="text" class="form-control" id="phone_desc_{{$phoneNumber->id}}" value="{{$phoneNumber->phone_desc}}"/></td>
                                                <td class="text-center"><button type="button" class="btn btn-primary send_back" id="send_back_{{$phoneNumber->id}}">Send Back</button></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <caption class="lease_table_caption">Pushed Phone Numbers </caption>
                                        </tfoot>
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection