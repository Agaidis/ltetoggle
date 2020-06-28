@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Owners Page</div>
                    <div class="card-body">
                        <h2 style="text-align:center;">Owner: {{$ownerName}}</h2>
                        <h3 style="text-align:center;">Address: {{$ownerLeaseData[0]->owner_address}}<br>{{$ownerLeaseData[0]->owner_city}}, {{$ownerLeaseData[0]->owner_state}}</h3>

                        <div class="row">
                             <div class="col-md-4">
                                 <h3 style="text-align:center;">Phone Numbers</h3>
                                 <div>
                                     <table class="table table-hover table-responsive-md table-bordered" id="owner_phone_table">
                                         <thead>
                                         <tr>
                                             <th class="text-center">Phone Description</th>
                                             <th class="text-center">Phone Number</th>
                                         </tr>
                                         </thead>
                                         <tbody>
                                         @foreach ($ownerPhoneNumbers as $ownerPhoneNumber)
                                             <tr>
                                                 @if ($ownerPhoneNumber->soft_delete === 1)
                                                     <td class="text-center" style="color:red; font-weight:bold">{{$ownerPhoneNumber->phone_desc}}</td>
                                                     @else
                                                     <td class="text-center" style="font-weight:bold">{{$ownerPhoneNumber->phone_desc}}</td>
                                                     @endif
                                                 <td class="text-center"><a href="tel:{{$ownerPhoneNumber->phone_number}}">{{$ownerPhoneNumber->phone_number}}</a></td>
                                             </tr>
                                         @endforeach
                                         </tbody>
                                     </table>
                                 </div>
                             </div>
                        </div>
                            <div class="col-md-12">
                                <h3 style="text-align:center;">Leases</h3>
                                <div>
                                    <table class="table table-hover table-responsive-md table-bordered" id="owner_lease_table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Id</th>
                                            <th class="text-center">Lease Name</th>
                                            <th style="width:20%;" class="text-center">Lease Description</th>
                                            <th class="text-center">ODI</th>
                                            <th class="text-center">Lease Notes</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $count = 0; ?>
                                        @foreach ($ownerLeaseData as $ownerLease)
                                            <tr>
                                                <td class="text-center"><?php echo $count ?> </td>
                                                @if ($permitObj[$count]['lease_name'] != '')
                                                    <td class="text-center"><a href="{{url( 'mineral-owner/' . $ownerLease->lease_name . '/' . $permitObj[$count]['reported_operator'] . '/' . $permitObj[$count]['id'])}}">{{$ownerLease->lease_name}}</a></td>
                                                @else
                                                    <td class="text-center">{{$ownerLease->lease_name}}</td>
                                                @endif
                                                    <td class="text-center">{{$ownerLease->lease_description}}</td>
                                                <td class="text-center">{{$ownerLease->owner_decimal_interest}}</td>
                                                @if ($noteArray[$count]['lease_name'] === $ownerLease->lease_name)
                                                    <td class="text-center"><div class="owner_notes" contenteditable="false">{!! $noteArray[$count]['notes'] !!}</div></td>
                                                @else
                                                    <td class="text-center"></td>
                                                @endif

                                            </tr>
                                            <?php $count++; ?>
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
@endsection
