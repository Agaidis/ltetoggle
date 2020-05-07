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

                            <div id="price_container" style="padding-bottom:5px;" class="offset-2 col-md-5">
                                <div class="row">
                                <!-- Oil Price Script - OILCRUDEPRICE.COM -->
                                <div class="offset-2 col-md-4" style="width:100%; border:1px solid grey; font-size:18px; font-weight:bold;text-align:center; padding-top:0px;">
                                    <a href="https://www.oilcrudeprice.com/" rel="nofollow" style="color:#1b1e21;">Wti Oil Price</a>
                                        <script type="text/javascript" src="https://www.oilcrudeprice.com/oilwidget.php?l=en&m=000000&g=ffffff&c=2d6ab4&i=ffffff&l=2d6ab4&o=e6f2fa&u=wti"></script>
                                    </div>

                                <!-- Natural Gass Price Script - OILCRUDEPRICE.COM -->
                                    <div class="offset-2 col-md-4" style="width:100%; border:1px solid grey; font-size:18px; font-weight:bold;text-align:center; padding-top:0px;">
                                        <a href="https://www.oilcrudeprice.com/" rel="nofollow" style="color:#1b1e21;">Natural Gas Price</a>
                                       <script type="text/javascript" src="https://www.oilcrudeprice.com/oilwidget.php?l=en&m=000000&g=ffffff&c=2d6ab4&i=ffffff&l=2d6ab4&o=e6f2fa&u=gas"></script>
                                    </div>
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
                                            <th class="text-center">Toggle Status</th>
                                            <th class="text-center">Assignee</th>
                                            <th class="text-center">State / County</th>
                                            <th class="text-center">Reported Operator</th>
                                            <th class="text-center">Lease Name</th>
                                        @else
                                            <th class="text-center">Col 1</th>
                                            <th class="text-center">Col 2</th>
                                            <th class="text-center">Col 3</th>
                                            <th class="text-center">Col 4</th>
                                            <th class="text-center">Col 5</th>
                                            <th class="text-center">Lease Name</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (isset($permits) && !$permits->isEmpty())
                                    @foreach ($permits as $permit)
                                        <?php $approvedDate = explode('T', $permit->approved_date)?>
                                        <input type="hidden" id="reported_operator_{{$permit->permit_id}}" value="{{$permit->reported_operator}}"/>

                                        <tr class="permit_row" id="permit_row_{{$permit->permit_id}}">

                                            @if (Auth::user()->role === 'admin')
                                                <td id="id_{{$permit->permit_id}}" class="text-center mmp-details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                    <td>
                                                            @if ($permit->toggle_status == 'black' || $permit->is_seen == 0)
                                                            <select id="toggle_status_{{$permit->permit_id}}" class="form-control toggle_status unseen">
                                                                <option value="none">Select Status</option>
                                                                <option selected value="black">Untouched</option>
                                                                <option value="blue">Major Prospect </option>
                                                                <option value="green">Quality Prospect </option>
                                                                <option value="red">Not Pursuing </option>
                                                            </select>
                                                            @elseif ($permit->toggle_status == 'blue')
                                                            <select id="toggle_status_{{$permit->permit_id}}" class="form-control toggle_status blue">
                                                                <option value="none">Select Status</option>
                                                                <option value="black">Untouched </option>
                                                                <option selected value="blue">Major Prospect </option>
                                                                <option value="green">Quality Prospect </option>
                                                                <option value="red">Not Pursuing </option>
                                                            </select>
                                                            @elseif ($permit->toggle_status == 'green')
                                                            <select id="toggle_status_{{$permit->permit_id}}" class="form-control toggle_status green">
                                                                <option value="none">Select Status</option>
                                                                <option value="black">Untouched </option>
                                                                <option value="blue">Major Prospect </option>
                                                                <option selected value="green">Quality Prospect </option>
                                                                <option value="red">Not Pursuing </option>
                                                            </select>
                                                            @elseif ($permit->toggle_status == 'red')
                                                            <select id="toggle_status_{{$permit->permit_id}}" class="form-control toggle_status red">
                                                                <option value="none">Select Status</option>
                                                                <option value="black">Untouched </option>
                                                                <option value="blue">Major Prospect </option>
                                                                <option value="green">Quality Prospect </option>
                                                                <option selected value="red">Not Pursuing </option>
                                                            </select>
                                                            @else
                                                            <select id="toggle_status_{{$permit->permit_id}}" class="form-control toggle_status">
                                                                <option value="none">Select Status</option>
                                                                <option value="black">Untouched </option>
                                                                <option value="blue">Major Prospect </option>
                                                                <option value="green">Quality Prospect </option>
                                                                <option value="red">Not Pursuing </option>
                                                            </select>
                                                            @endif

                                                    </td>
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
