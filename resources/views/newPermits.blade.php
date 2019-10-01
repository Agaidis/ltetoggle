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
                                        <th class="text-center">More Data</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($decodedPermits as $permit => $data)
                                        <?php $count = count($data); ?>
                                        @for ($i = 0; $i < $count; $i++)
                                        <?php $approvedDate = explode('T', $data[$i]->ApprovedDate)?>
                                        @if (($data[$i]->DrillType == 'H' || $data[$i]->DrillType == 'V') && ($data[$i]->WellType == 'GAS' || $data[$i]->WellType == 'OIL'))
                                            <tr>
                                                <td class="text-center">{{$approvedDate[0]}}</td>
                                                <td class="text-center">{{$data[$i]->ContactName}}</td>
                                                <td class="text-center">{{$data[$i]->ContactPhone}}</td>
                                                <td class="text-center">{{$data[$i]->CountyParish}}</td>
                                                <td class="text-center">{{$data[$i]->DrillType}}</td>
                                                <td class="text-center">{{$data[$i]->WellType}}</td>
                                                <td class="text-center">
                                                    <button type="button" data-target="#modal_show_permit" data-toggle="modal" id="id_{{$data[$i]->PermitID}}" class="fa fa-edit btn-sm view_permit"></button>
                                                </td>
                                            </tr>
                                        @endif
                                        @endfor
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <caption id="lease_table_caption">Permits</caption>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="modal fade" id="modal_show_permit">
                            <div class="modal-dialog" role="document">
                                <div style="width:150%; margin-left:-116px;" class="set_court_date_modal modal-content">
                                    <div class="modal-header">
                                        <h4>Permit Data: </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                                <table class="table table-bordered table-hover table-dark">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Value</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td class="text-center">API10</td>
                                                        <td class="text-center" id="API10"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">API12</td>
                                                        <td class="text-center" id="API12"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Abstract</td>
                                                        <td class="text-center" id="Abstract"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">AmendmentFiledDate</td>
                                                        <td class="text-center" id="AmendmentFiledDate"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">ApprovedDate</td>
                                                        <td class="text-center" id="ApprovedDate"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Block</td>
                                                        <td class="text-center" id="Block"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">BottomHoleLatitudeWGS84</td>
                                                        <td class="text-center" id="BottomHoleLatitudeWGS84"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">BottomHoleLongitudeWGS84</td>
                                                        <td class="text-center" id="BottomHoleLongitudeWGS84"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">ContactName</td>
                                                        <td class="text-center" id="ContactName"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">ContactPhone</td>
                                                        <td class="text-center" id="ContactPhone"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">CountyParish</td>
                                                        <td class="text-center" id="CountyParish"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">CreatedDate</td>
                                                        <td class="text-center" id="CreatedDate"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">DeletedDate</td>
                                                        <td class="text-center" id="DeletedDate"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">District</td>
                                                        <td class="text-center" id="District"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">DrillType</td>
                                                        <td class="text-center" id="DrillType"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">ExpiredDate</td>
                                                        <td class="text-center" id="ExpiredDate"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Field</td>
                                                        <td class="text-center" id="Field"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Formation</td>
                                                        <td class="text-center" id="Formation"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">H2SArea</td>
                                                        <td class="text-center" id="H2SArea"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">LeaseName</td>
                                                        <td class="text-center" id="LeaseName"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">LeaseNumber</td>
                                                        <td class="text-center" id="LeaseNumber"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">OFSRegion</td>
                                                        <td class="text-center" id="OFSRegion"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">OperatorAddress</td>
                                                        <td class="text-center" id="OperatorAddress"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">OperatorAlias</td>
                                                        <td class="text-center" id="OperatorAlias"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">OperatorCity</td>
                                                        <td class="text-center" id="OperatorCity"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">OperatorCity30mi</td>
                                                        <td class="text-center" id="OperatorCity30mi"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">OperatorCity50mi</td>
                                                        <td class="text-center" id="OperatorCity50mi"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">OperatorState</td>
                                                        <td class="text-center" id="OperatorState"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">OperatorZip</td>
                                                        <td class="text-center" id="OperatorZip"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">OrigApprovedDate</td>
                                                        <td class="text-center" id="OrigApprovedDate"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">PermitDepth</td>
                                                        <td class="text-center" id="PermitDepth"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">PermitDepthUOM</td>
                                                        <td class="text-center" id="PermitDepthUOM"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">PermitID</td>
                                                        <td class="text-center" id="PermitID"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">PermitNumber</td>
                                                        <td class="text-center" id="PermitNumber"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">PermitStatus</td>
                                                        <td class="text-center" id="PermitStatus"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">PermitType</td>
                                                        <td class="text-center" id="PermitType"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Range</td>
                                                        <td class="text-center" id="Range"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">ReportedOperator</td>
                                                        <td class="text-center" id="ReportedOperator"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Section</td>
                                                        <td class="text-center" id="Section"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">StateProvince</td>
                                                        <td class="text-center" id="StateProvince"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">SubmittedDate</td>
                                                        <td class="text-center" id="SubmittedDate"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">SurfaceLatitudeWGS84</td>
                                                        <td class="text-center" id="SurfaceLatitudeWGS84"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">SurfaceLongitudeWGS84</td>
                                                        <td class="text-center" id="SurfaceLongitudeWGS84"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Survey</td>
                                                        <td class="text-center" id="Survey"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">Township</td>
                                                        <td class="text-center" id="Township"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">TrueVerticalDepth</td>
                                                        <td class="text-center" id="TrueVerticalDepth"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">TrueVerticalDepthUOM</td>
                                                        <td class="text-center" id="TrueVerticalDepthUOM"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">UpdatedDate</td>
                                                        <td class="text-center" id="UpdatedDate"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">WGID</td>
                                                        <td class="text-center" id="WGID"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">WellNumber</td>
                                                        <td class="text-center" id="WellNumber"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">WellStatus</td>
                                                        <td class="text-center" id="WellStatus"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">WellType</td>
                                                        <td class="text-center" id="WellType"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="submit_date" class="approve-btn btn btn-success" data-dismiss="modal">Update Notes</button>
                                        <button type="button" id="cancel_date" class="approve-btn btn btn-primary" data-dismiss="modal" >Exit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection