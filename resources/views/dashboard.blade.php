@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        <div class="row">
                            <div id="dashboard_btn_container" class="col-md-4">
                                <div class="button_panel">
                                    <a href="{{ url('welbore') }}"><button type="button" class="btn btn-primary dashboard_btns" id="welbore_btn">Wellbore</button></a>
                                    <a href="{{ url('new-permits') }}"><button type="button" class="btn btn-primary dashboard_btns" id="abstract_btn">Permits</button></a>
                                </div>
                            </div>
                            <div id="dashboard_gas_price_container">
                                <div class="gas_panel">
                                    <span class="gas_text">Oil & Gas</span><br>
                                    <span class="gas_text">Daily Price</span>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                            <table class="table table-hover table-responsive-md table-bordered" id="lease_table">
                                <thead>
                                <tr>
                                    <th class="text-center">Lease Id</th>
                                    <th class="text-center">State</th>
                                    <th class="text-center">County Parish</th>
                                    <th class="text-center">Area Acres</th>
                                    <th class="text-center">Created Date</th>
                                    <th class="text-center">DI Link</th>
                                    <Th class="text-center">More Data</Th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($leases as $lease)
                                    <?php $createdDate = explode('T', $lease->CreatedDate)?>
                                <tr>
                                    <td class="text-center">{{$lease->LeaseId}}</td>
                                    <td class="text-center">{{$lease->State}}</td>
                                    <td class="text-center">{{$lease->CountyParish}}</td>
                                    <td class="text-center">{{$lease->AreaAcres}}</td>
                                    <td class="text-center">{{$createdDate[0]}}</td>
                                    <td class="text-center"><a href="{{$lease->DILink}}">DI Ref</a></td>
                                    <td class="text-center">
                                        <button type="button" data-target="#modal_show_lease" data-toggle="modal" id="id_{{$lease->LeaseId}}" class="fa fa-edit btn-sm view_lease"></button>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <caption id="lease_table_caption">Leases: Non-Producing & Producing</caption>
                                </tfoot>
                            </table>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal fade" id="modal_show_lease">
                    <div class="modal-dialog" role="document">
                        <div style="width:150%; margin-left:-116px;" class="set_court_date_modal modal-content">
                            <div class="modal-header">
                                <h4>Lease Data: </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-offset-3 col-sm-8">
                                        <table class="table table-bordered table-hover table-dark">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="text-center">Area Acres</td>
                                                <td class="text-center" id="areaAcres"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">BLM</td>
                                                <td class="text-center" id="BLM"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Bonus</td>
                                                <td class="text-center" id="Bonus"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">CentroidLatitude</td>
                                                <td class="text-center" id="CentroidLatitude"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">CentroidLongitude</td>
                                                <td class="text-center" id="CentroidLongitude"></td>
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
                                                <td class="text-center">DIBasin</td>
                                                <td class="text-center" id="DIBasin"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">DILink</td>
                                                <td class="text-center" id="DILink"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">DIPlay</td>
                                                <td class="text-center" id="DIPlay"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">DISubPlay</td>
                                                <td class="text-center" id="DISubPlay"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">DeletedDate</td>
                                                <td class="text-center" id="DeletedDate"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">DepthClauseAvailable</td>
                                                <td class="text-center" id="DepthClauseAvailable"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">DepthClauseTypes</td>
                                                <td class="text-center" id="DepthClauseTypes"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">EffectiveDate</td>
                                                <td class="text-center" id="EffectiveDate"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">ExpirationofPrimaryTerm</td>
                                                <td class="text-center" id="ExpirationofPrimaryTerm"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">ExtBonus</td>
                                                <td class="text-center" id="ExtBonus"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">ExtTermMonths</td>
                                                <td class="text-center" id="ExtTermMonths"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Geometry</td>
                                                <td class="text-center" id="Geometry"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Grantee</td>
                                                <td class="text-center" id="Grantee"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">GranteeAddress</td>
                                                <td class="text-center" id="GranteeAddress"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">GranteeAlias</td>
                                                <td class="text-center" id="GranteeAlias"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">InstrumentDate</td>
                                                <td class="text-center" id="InstrumentDate"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">InstrumentType</td>
                                                <td class="text-center" id="InstrumentType"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">LeaseId</td>
                                                <td class="text-center" id="LeaseId"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">MajorityAssignmentEffectiveDate</td>
                                                <td class="text-center" id="MajorityAssignmentEffectiveDate"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">MajorityAssignmentVolPage</td>
                                                <td class="text-center" id="MajorityAssignmentVolPage"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">MajorityLegalAssignee</td>
                                                <td class="text-center" id="MajorityLegalAssignee"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">MajorityLegalAssigneeInterest</td>
                                                <td class="text-center" id="MajorityLegalAssigneeInterest"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">MaxDepth</td>
                                                <td class="text-center" id="MaxDepth"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">MinDepth</td>
                                                <td class="text-center" id="MinDepth"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Nomination</td>
                                                <td class="text-center" id="Nomination"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">OptionsExtensions</td>
                                                <td class="text-center" id="OptionsExtensions"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">RecordDate</td>
                                                <td class="text-center" id="RecordDate"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">RecordNo</td>
                                                <td class="text-center" id="RecordNo"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Remarks</td>
                                                <td class="text-center" id="Remarks"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Royalty</td>
                                                <td class="text-center" id="Royalty"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">SpatialAssignee</td>
                                                <td class="text-center" id="SpatialAssignee"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">State</td>
                                                <td class="text-center" id="State"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">StateLease</td>
                                                <td class="text-center" id="StateLease"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">TermMonths</td>
                                                <td class="text-center" id="TermMonths"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">UpdatedDate</td>
                                                <td class="text-center" id="UpdatedDate"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">VolPage</td>
                                                <td class="text-center" id="VolPage"></td>
                                            </tr>














                                            </tbody>
                                        </table>

                                    </div>
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
@endsection
