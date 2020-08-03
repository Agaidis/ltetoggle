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
                                    @if ($userRole == 'admin')
                                        <a href="{{ url('welbore') }}"><button type="button" class="btn btn-primary dashboard_btns" id="welbore_btn">Wellbore</button></a>
                                        <a href="{{ url('mm-platform') }}">
                                            <button style="margin-left:5%;" type="button" class="btn btn-primary dashboard_btns" id="user_mmp_btn">MMP General</button>
                                        </a>
                                        <a href="{{ url('justus-mmp') }}">
                                            <button style="margin-left:5%;" type="button" class="btn btn-primary dashboard_btns">Justus Danna</button>
                                        </a>
                                    @elseif ($userRole == 'regular')
                                        <a href="{{ url('welbore') }}">
                                            <button type="button" class="btn btn-primary dashboard_btns" id="welbore_btn">Wellbore</button>
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


                        <div id="myTab" class="interest_nav_container">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item nav-link interest_tab" id="interest_tab_eagle">
                                    <a href="#eagle_interest_area" role="tab" data-toggle="tab" class="interest_text" aria-controls="eagle_interest_area">Eagle Ford Interest Area</a>
                                </li>
                                <li class="nav-item nav-link interest_tab" id="interest_tab_wtx">
                                    <a href="#wtx_nm_interest_area" role="tab" data-toggle="tab" class="interest_text" aria-controls="wtx_nm_interest_area">WTX/NM Interest Area</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- EAGLE INTEREST AREA TAB -->
                                <div class="tab-pane active" role="tabpanel" id="eagle_interest_area">
                                    <div class="row">
                                        <div class="offset-1 col-md-10">
                                            <table class="table table-hover table-responsive-md table-bordered permit_table" id="eagle_permit_table">
                                                <thead>
                                                <tr>
                                                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'regular')
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
                                                @if (isset($eaglePermits) && !$eaglePermits->isEmpty())
                                                    @foreach ($eaglePermits as $eaglePermit)
                                                        <?php $approvedDate = explode('T', $eaglePermit->approved_date)?>
                                                        <input type="hidden" id="reported_operator_{{$eaglePermit->permit_id}}" value="{{$eaglePermit->reported_operator}}"/>
                                                        <tr class="permit_row" id="permit_row_{{$eaglePermit->permit_id}}">
                                                            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'regular')
                                                                <td id="id_{{$eaglePermit->permit_id}}" class="text-center mmp-details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                                <td>
                                                                    @if ($eaglePermit->toggle_status == 'yellow' || $eaglePermit->is_seen == 0)
                                                                        <select id="toggle_status_{{$eaglePermit->permit_id}}" class="form-control toggle_status unseen">
                                                                            <option selected value="yellow">Untouched</option>
                                                                            <option value="green">Major Prospect</option>
                                                                            <option value="blue">Quality Prospect</option>
                                                                            <option value="red">Active but paused</option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($eaglePermit->toggle_status == 'green')
                                                                        <select id="toggle_status_{{$eaglePermit->permit_id}}" class="form-control toggle_status green">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option selected value="green">Major Prospect</option>
                                                                            <option value="blue">Quality Prospect</option>
                                                                            <option value="red">Active but paused</option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($eaglePermit->toggle_status == 'blue')
                                                                        <select id="toggle_status_{{$eaglePermit->permit_id}}" class="form-control toggle_status blue">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect</option>
                                                                            <option selected value="blue">Quality Prospect</option>
                                                                            <option value="red">Active but paused</option>
                                                                            <option selected value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($eaglePermit->toggle_status == 'red')
                                                                        <select id="toggle_status_{{$eaglePermit->permit_id}}" class="form-control toggle_status red">
                                                                            <option value="yellow">Untouched</option>
                                                                            <option value="green">Major Prospect</option>
                                                                            <option value="blue">Quality Prospect</option>
                                                                            <option selected value="red">Active but paused</option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($eaglePermit->toggle_status == 'purple')
                                                                        <select id="toggle_status_{{$eaglePermit->permit_id}}" class="form-control toggle_status purple">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option selected value="purple">Completed</option>
                                                                        </select>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <select class="form-control assignee" id="assignee_{{$eaglePermit->permit_id}}">
                                                                        <option selected value="">Select a User</option>
                                                                        @foreach ($users as $user)
                                                                            @if ($eaglePermit->assignee == $user->id)
                                                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                                            @else
                                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="text-center">{{$eaglePermit->county_parish}}</td>
                                                                <td class="text-center">{{$eaglePermit->reported_operator}}</td>
                                                                <td class="text-center"><a href="{{url( 'mineral-owner/' . $eaglePermit->lease_name . '/' . $eaglePermit->reported_operator . '/' . $eaglePermit->id)}}">{{$eaglePermit->lease_name}}</a></td>
                                                            @else
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"><a href="{{url( 'mineral-owner/' . $eaglePermit->lease_name . '/' . $eaglePermit->reported_operator . '/' . $eaglePermit->id)}}">{{$eaglePermit->lease_name}}</a></td>
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

                                <!-- WTX NM INTEREST AREA -->
                                <div class="tab-pane interest_text" role="tabpanel" id="wtx_nm_interest_area" >
                                    <div class="row">
                                        <div class="offset-1 col-md-10">
                                            <table class="table table-hover table-responsive-md table-bordered permit_table" id="nvx_permit_table">
                                                <thead>
                                                <tr>
                                                    @if (Auth::user()->role === 'admin' || Auth::user()->role === 'regular')

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
                                                @if (isset($nvxPermits) && !$nvxPermits->isEmpty())
                                                    @foreach ($nvxPermits as $nvxPermit)
                                                        <?php $approvedDate = explode('T', $nvxPermit->approved_date)?>
                                                        <input type="hidden" id="reported_operator_{{$nvxPermit->permit_id}}" value="{{$nvxPermit->reported_operator}}"/>

                                                        <tr class="permit_row" id="permit_row_{{$nvxPermit->permit_id}}">

                                                            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'regular')
                                                                <td id="id_{{$nvxPermit->permit_id}}" class="text-center mmp-details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                                <td>
                                                                    @if ($nvxPermit->toggle_status == 'yellow' || $nvxPermit->is_seen == 0)
                                                                        <select id="toggle_status_{{$nvxPermit->permit_id}}" class="form-control toggle_status unseen">
                                                                            <option selected value="yellow">Untouched</option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nvxPermit->toggle_status == 'green')
                                                                        <select id="toggle_status_{{$nvxPermit->permit_id}}" class="form-control toggle_status blue">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option selected value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nvxPermit->toggle_status == 'blue')
                                                                        <select id="toggle_status_{{$nvxPermit->permit_id}}" class="form-control toggle_status green">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option selected value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nvxPermit->toggle_status == 'red')
                                                                        <select id="toggle_status_{{$nvxPermit->permit_id}}" class="form-control toggle_status red">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option selected value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nvxPermit->toggle_status == 'purple')
                                                                        <select id="toggle_status_{{$nvxPermit->permit_id}}" class="form-control toggle_status purple">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option selected value="purple">Completed</option>
                                                                        </select>
                                                                    @endif

                                                                </td>
                                                                <td class="text-center">
                                                                    <select class="form-control assignee" id="assignee_{{$nvxPermit->permit_id}}">
                                                                        <option selected value="">Select a User</option>
                                                                        @foreach ($users as $user)
                                                                            @if ($nvxPermit->assignee == $user->id)
                                                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                                            @else
                                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="text-center">{{$nvxPermit->county_parish}}</td>
                                                                <td class="text-center">{{$nvxPermit->reported_operator}}</td>
                                                                <td class="text-center"><a href="{{url( 'mineral-owner/' . $nvxPermit->lease_name . '/' . $nvxPermit->reported_operator . '/' . $nvxPermit->id)}}">{{$nvxPermit->lease_name}}</a></td>
                                                            @else
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"><a href="{{url( 'mineral-owner/' . $nvxPermit->lease_name . '/' . $nvxPermit->reported_operator . '/' . $nvxPermit->id)}}">{{$nvxPermit->lease_name}}</a></td>

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
            </div>
        </div>
    </div>
@endsection
