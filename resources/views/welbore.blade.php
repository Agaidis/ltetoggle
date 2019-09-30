@extends('layouts.app')
@section('content')
    <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body body_container">
                        <h2 class="titles">Welbore Page</h2>
                            <table class="table table-hover table-responsive-md table-bordered" id="table_one">
                                <thead>
                                <tr>
                                    <th class="text-center">Well Id</th>
                                    <th class="text-center">WellBore Id</th>
                                    <th class="text-center">Basin Name</th>
                                    <th class="text-center">Status Date</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">County</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($wellBores as $wellBore)
                                    <?php $CurrentStatusDate = explode('T', $wellBore->CurrentStatusDate)?>
                                        <tr>
                                            <td class="text-center">{{$wellBore->WellID}}</td>
                                            <td class="text-center">{{$wellBore->WellboreID}}</td>
                                            <td class="text-center">{{$wellBore->BasinName}}</td>
                                            <td class="text-center">{{$CurrentStatusDate[0]}}</td>
                                            <td class="text-center">{{$wellBore->WellboreType}}</td>
                                            <td class="text-center">{{$wellBore->County}}</td>
                                        </tr>
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
@endsection