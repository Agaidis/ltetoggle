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
{{--                                            <th class="text-center">Owner Numbers</th>--}}
                                            <th class="text-center">Owner Name</th>
                                            <th class="text-center">Description</th>
                                            <th class="text-center">Phone Number</th>
                                            <th class="text-center">Send Back</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($pushedPhoneNumbers as $pushedPhoneNumber)
                                            <tr class="phone_number_row {{$pushedPhoneNumber->id}}" id="phone_number_row_{{$pushedPhoneNumber->id}}">
{{--                                                <td id="id_{{$phoneNumber->id}}" class="text-center owner-numbers-control view_numbers"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>--}}
                                                <td class="text-center">{{$pushedPhoneNumber->owner_name}}</td>
                                                <td class="text-center"><input type="text" class="form-control" id="phone_desc_{{$pushedPhoneNumber->id}}" value="{{$pushedPhoneNumber->phone_desc}}"/></td>
                                                <td class="text-center"><input type="text" class="form-control" id="phone_number_{{$pushedPhoneNumber->id}}" value="{{$pushedPhoneNumber->phone_number}}"/></td>
                                                <td class="text-center"><button type="button" class="btn btn-primary send_back" id="send_back_{{$pushedPhoneNumber->id}}">Send Back</button></td>
                                            </tr>
{{--                                            @foreach ($allNumbers as $number)--}}
{{--                                                @if (in_array($number->owner_name, $ownerArray) && $pushedPhoneNumber->id != $number->id )--}}
{{--                                                    @if ($number->soft_delete == 0)--}}
{{--                                                        <tr class="{{$pushedPhoneNumber->id}}">--}}
{{--                                                            <td style="color:darkgrey;" class="text-center">{{$pushedPhoneNumber->owner_name}}</td>--}}
{{--                                                            <td class="text-center">{{$number->phone_desc}}</td>--}}
{{--                                                            <td class="text-center">{{$number->phone_number}}</td>--}}
{{--                                                            <td class="text-center"></td>--}}
{{--                                                        </tr>--}}
{{--                                                    @else--}}
{{--                                                        <tr class="{{$pushedPhoneNumber->id}}">--}}
{{--                                                            <td style="color:darkgrey;" class="text-center">{{$pushedPhoneNumber->owner_name}}</td>--}}
{{--                                                            <td style="color:red;"class="text-center">{{$number->phone_desc}}</td>--}}
{{--                                                            <td style="color:red;"class="text-center">{{$number->phone_number}}</td>--}}
{{--                                                            <td class="text-center"></td>--}}
{{--                                                        </tr>--}}
{{--                                                    @endif--}}
{{--                                                @endif--}}
{{--                                            @endforeach--}}

                                            <tr class="{{$pushedPhoneNumber->id}}">
                                                <td style="color:darkgrey;" class="text-center">{{$pushedPhoneNumber->owner_name}}</td>
                                                <td class="text-center"><input type="text" class="form-control" id="insert_phone_desc_{{$pushedPhoneNumber->id}}" value=""/></td>
                                                <td class="text-center"><input type="text" class="form-control" id="insert_phone_number_{{$pushedPhoneNumber->id}}" value=""/></td>
                                                <td class="text-center"><button type="button" class="btn btn-primary insert_number" id="insert_number_{{$pushedPhoneNumber->id}}">Insert Number</button></td>
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