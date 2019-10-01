@extends('layouts.app')
@section('content')
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body body_container">
                        <h2 class="titles">Permits</h2>
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <table class="table table-hover table-responsive-md table-bordered" id="permit_table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Approved Date</th>
                                        <th class="text-center">Contact Name</th>
                                        <th class="text-center">Contact Phone</th>
                                        <th class="text-center">Contact Parish</th>
                                        <th class="text-center">Drill Type</th>
                                        <th class="text-center">Well Type</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($permits as $permit)
                                        <?php $approvedDate = explode('T', $permit->ApprovedDate)?>
                                        @if (($permit->DrillType == 'H' || $permit->DrillType == 'V') && ($permit->WellType == 'GAS' || $permit->WellType == 'OIL'))
                                            <tr>
                                                <td class="text-center">{{$approvedDate[0]}}</td>
                                                <td class="text-center">{{$permit->ContactName}}</td>
                                                <td class="text-center">{{$permit->ContactPhone}}</td>
                                                <td class="text-center">{{$permit->CountyParish}}</td>
                                                <td class="text-center">{{$permit->DrillType}}</td>
                                                <td class="text-center">{{$permit->WellType}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <caption id="lease_table_caption">Permits</caption>
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