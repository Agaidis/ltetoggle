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
                                    <a href="{{ url('new-permits') }}"><button type="button" class="btn btn-primary dashboard_btns" id="abstract_btn">Abstract</button></a>
                                </div>
                            </div>
                            <div id="dashboard_gas_price_container">
                                <div class="gas_panel">
                                    <span class="gas_text">O:14 Gas</span><br>
                                    <span class="gas_text">Daily Price</span>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                {{$data}}

                            <table class="table table-hover table-responsive-md table-bordered" id="table_one">
                                <thead>
                                <tr>
                                    <th class="text-center">Lease Id</th>
                                    <th class="text-center">Acreage</th>
                                    <th class="text-center">State</th>
                                    <th class="text-center">Block 4 Section</th>
                                    <th class="text-center">6IS (TRAC)</th>
                                    <th class="text-center">Action</th>

                                </tr>
                                </thead>
                                <tbody>
{{--                                @foreach ($data as $lease)--}}
{{--                                <tr>--}}
{{--                                    <td class="text-center">{{$lease->LeaseId}}</td>--}}
{{--                                    <td class="text-center">{{$lease->AcreaAcres}}</td>--}}
{{--                                    <td class="text-center">{{$lease->State}}</td>--}}
{{--                                        <button type="button" data-target="#modal_edit_magistrate" data-toggle="modal" class="pdf_download_btn_dashboard btn-sm btn-primary fas fa-edit"></button>--}}
{{--                                        <button type="button" class="fa fa-trash btn-sm btn-danger"></button>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}

{{--                                @endforeach--}}
                                </tbody>
                                <tfoot>
                                <caption id="lease_table_caption">Leases: Non-Producing & Producing</caption>
                                </tfoot>
                            </table>
                            </div>
                        </div>
                        <div class="modal fade" id="modal_edit_magistrate">
                            <div class="modal-dialog" role="document">
                                <div style="width:150%; margin-left:-116px;" class="set_court_date_modal modal-content">
                                    <div class="modal-header">
                                        <h4 class="set_court_date_title">Lease Data: </h4>
                                        <span>This is where we would do any editing/updating of a certain row.</span>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-sm-8">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" id="submit_date" class="approve-btn btn btn-success" data-dismiss="modal">Update Data</button>
                                        <button type="button" id="cancel_date" class="approve-btn btn btn-primary" data-dismiss="modal" >Cancel</button>
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
