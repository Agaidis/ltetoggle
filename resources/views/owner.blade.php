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

                        <div class="row">
                             <div class="col-offset-2 col-md-4">
                                 <div>
                                     <table class="table table-hover table-responsive-md table-bordered" id="owner_table">
                                         <thead>
                                         <tr>
                                             <th class="text-center">Phone Description</th>
                                             <th class="text-center">Phone Number</th>
                                         </tr>
                                         </thead>
                                         <tbody>
                                         @foreach ($ownerPhoneNumbers as $ownerPhoneNumber)
                                             <tr>
                                                 <td class="text-center">{{$ownerPhoneNumber->phone_desc}}</td>
                                                 <td class="text-center">{{$ownerPhoneNumber->phone_number}}</td>
                                             </tr>
                                         @endforeach

                                         </tbody>
                                     </table>
                                 </div>
                             </div>
                            <div class="col-offset-2 col-md-4">
                                <div>
                                    <table class="table table-hover table-responsive-md table-bordered" id="owner_table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Notes</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($ownerNotes as $ownerNote)
                                            <tr>
                                                <td class="text-center">{!! $ownerNote->notes !!}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
