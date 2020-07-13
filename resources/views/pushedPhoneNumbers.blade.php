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
                                    <table class="table table-responsive-md" id="phone_numbers_table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Owner Name</th>
                                            <th class="text-center">Lease Name</th>
                                            <th class="text-center">Description</th>
                                            <th class="text-center">Phone Number</th>
                                            <th class="text-center">Send Back</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $currentOwnerName = ''; $previousOwnerName = ''; $phoneDescriptions = ''; $phoneNumbers = '';?>
                                            @for ($i = 0; $i < count($pushedPhoneNumbers); $i++)
                                                <?php $currentOwnerName = $pushedPhoneNumbers[$i]->owner_name;
                                                ?>

                                                @if ($i == 0 || $currentOwnerName != $previousOwnerName)
                                                    <tr class="phone_number_row" id="phone_number_row_{{$pushedPhoneNumbers[$i]->id}}">
                                                        <td class="text-center">{{$pushedPhoneNumbers[$i]->owner_name}}<br>{{$pushedPhoneNumbers[$i]->owner_address}}<br>{{$pushedPhoneNumbers[$i]->owner_city}}, {{$pushedPhoneNumbers[$i]->owner_state}} {{$pushedPhoneNumbers[$i]->owner_zip}}</td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"><input type="text" class="form-control" id="insert_phone_desc_{{$pushedPhoneNumbers[$i]->id}}" value=""/></td>
                                                        <td class="text-center"><input type="text" class="form-control" id="insert_phone_number_{{$pushedPhoneNumbers[$i]->id}}" value=""/></td>
                                                        <td class="text-center"><button type="button" class="btn btn-success insert_number" id="insert_number_{{$pushedPhoneNumbers[$i]->id}}">New Number</button></td>
                                                    </tr>
                                                @endif

                                                <tr class="{{$pushedPhoneNumbers[$i]->id}}">
                                                    <td class="text-center" style="border:none; color:white;">{{$pushedPhoneNumbers[$i]->owner_name}}</td>
                                                    <td class="text-center" style="border:none;">{{$pushedPhoneNumbers[$i]->lease_name}}</td>
                                                    <td class="text-center" style="border:none;"><input type="text" class="form-control" id="phone_desc_{{$pushedPhoneNumbers[$i]->id}}" value="{{$pushedPhoneNumbers[$i]->phone_desc}}"/></td>
                                                    <td class="text-center" style="border:none;"><input type="text" class="form-control" id="phone_number_{{$pushedPhoneNumbers[$i]->id}}" value="{{$pushedPhoneNumbers[$i]->phone_number}}"/></td>
                                                    <td class="text-center" style="border:none;"><button type="button" class="btn btn-primary send_back" id="send_back_{{$pushedPhoneNumbers[$i]->id}}">Send Back</button></td>
                                                </tr>
                                                <?php $previousOwnerName = $pushedPhoneNumbers[$i]->owner_name; ?>
                                        @endfor
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