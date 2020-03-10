@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Mineral Management Platform</div>
                    <div class="card-body">
                        <div class="row">
                            <div id="dashboard_btn_container" class="col-md-4">
                                <div class="button_panel">
                                    @if (Auth::user()->role === 'admin')
                                    <a href="{{ url('welbore') }}"><button type="button" class="btn btn-primary dashboard_btns" id="welbore_btn">Wellbore</button></a>
                                    <a href="{{ url('user-mmp') }}">
                                        <button style="margin-left:5%;" type="button" class="btn btn-primary dashboard_btns" id="user_mmp_btn">{{Auth::user()->name}}</button>
                                    </a>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-1 col-md-10">
                                <table class="table table-hover table-responsive-md table-bordered" id="permit_table">
                                    <thead>
                                    <tr>
                                        @if (Auth::user()->role === 'admin')
                                            <th class="text-center">Open Lease</th>
                                            <th class="text-center">Assignee</th>
                                            <th class="text-center">State / County</th>
                                            <th class="text-center">Reported Operator</th>
                                            <th class="text-center">Lease Name</th>
                                        @else
                                            <th class="text-center">Col 1</th>
                                            <th class="text-center">Col 2</th>
                                            <th class="text-center">Col 3</th>
                                            <th class="text-center">Lease Name</th>
                                            <th class="text-center">Col 5</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (isset($permits) && !$permits->isEmpty())
                                    @foreach ($permits as $permit)
                                        <?php $approvedDate = explode('T', $permit->approved_date)?>
                                        <input type="hidden" id="reported_operator_{{$permit->permit_id}}" value="{{$permit->reported_operator}}"/>

                                        @if ($permit->is_seen == 1)
                                            <tr class="permit_row" id="permit_row_{{$permit->permit_id}}">
                                        @else
                                            <tr class="permit_row unseen" id="permit_row_{{$permit->permit_id}}">
                                        @endif

                                            @if (Auth::user()->role === 'admin')
                                                <td id="id_{{$permit->permit_id}}" class="text-center mmp-details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                <td class="text-center">
                                                    <select class="form-control assignee" id="assignee_{{$permit->permit_id}}">
                                                        <option selected value="">Select a User</option>
                                                        @foreach ($users as $user)
                                                            @if ($permit->assignee == $user->id)
                                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                            @else
                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">{{$permit->county_parish}}</td>
                                                <td class="text-center">{{$permit->reported_operator}}</td>
                                                <td class="text-center"><a href="{{url( 'mineral-owner/' . $permit->lease_name . '/' . $permit->reported_operator . '/' . $permit->id)}}">{{$permit->lease_name}}</a></td>
                                                @else
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"><a href="{{url( 'mineral-owner/' . $permit->lease_name . '/' . $permit->reported_operator . '/' . $permit->id)}}">{{$permit->lease_name}}</a></td>

                                            @endif
                                        </tr>

                                    @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                    <caption class="lease_table_caption">Landtrac's Producing </caption>
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
