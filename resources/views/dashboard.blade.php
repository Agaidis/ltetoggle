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
                                        <a href="{{ url('wellbore/0') }}">
                                            <button type="button" class="btn btn-primary dashboard_btns" id="welbore_btn">Wellbore</button>
                                        </a>
                                        <a href="{{ url('user-mmp') }}">
                                            <button style="margin-left:5%;" type="button" class="btn btn-primary dashboard_btns" id="user_mmp_btn">{{Auth::user()->name}}</button>
                                        </a>
                                        <a href="{{ url('justus-mmp') }}">
                                            <button style="margin-left:5%;" type="button" class="btn btn-primary dashboard_btns">Justus Danna</button>
                                        </a>
                                        @elseif ($userRole == 'regular')
                                        <a href="{{ url('wellbore/0') }}">
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
                                    <a href="#eagle_interest_area" role="tab" data-toggle="tab" class="interest_text" aria-controls="eagle_interest_area">Eagleford Interest Area</a>
                                </li>
                                <li class="nav-item nav-link interest_tab" id="interest_tab_wtx">
                                    <a href="#wtx_interest_area" role="tab" data-toggle="tab" class="interest_text" aria-controls="wtx_interest_area">WTX Interest Area</a>
                                </li>
                                <li class="nav-item nav-link interest_tab" id="interest_tab_nm">
                                    <a href="#nm_interest_area" role="tab" data-toggle="tab" class="interest_text" aria-controls="nm_interest_area">NM Interest Area</a>
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
                                                    @if (Auth::user()->role === 'admin')
                                                        <th class="text-center">Store Lease</th>
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

                                            @if (Auth::user()->role === 'admin')
                                                <td class="text-center"><button type="button" class="store_button btn btn-primary" id="store_button_{{$eaglePermit->permit_id}}_{{$eaglePermit->lease_name}}">Store</button></td>
                                                <td id="id_{{$eaglePermit->permit_id}}" class="text-center mmp-details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                    <td>
                                                            @if ($eaglePermit->toggle_status == 'yellow')
                                                            <select id="toggle_status_{{$eaglePermit->permit_id}}" class="form-control toggle_status unseen">
                                                                <option selected value="yellow">Untouched</option>
                                                                <option value="green">Major Prospect </option>
                                                                <option value="blue">Quality Prospect </option>
                                                                <option value="red">Active but paused </option>
                                                                <option value="purple">Completed</option>
                                                            </select>
                                                            @elseif ($eaglePermit->toggle_status == 'green')
                                                            <select id="toggle_status_{{$eaglePermit->permit_id}}" class="form-control toggle_status green">
                                                                <option value="yellow">Untouched </option>
                                                                <option selected value="green">Major Prospect </option>
                                                                <option value="blue">Quality Prospect </option>
                                                                <option value="red">Active but paused </option>
                                                                <option value="purple">Completed</option>
                                                            </select>
                                                            @elseif ($eaglePermit->toggle_status == 'blue')
                                                            <select id="toggle_status_{{$eaglePermit->permit_id}}" class="form-control toggle_status blue">
                                                                <option value="yellow">Untouched </option>
                                                                <option value="green">Major Prospect </option>
                                                                <option selected value="blue">Quality Prospect </option>
                                                                <option value="red">Active but paused </option>
                                                                <option value="purple">Completed</option>
                                                            </select>
                                                            @elseif ($eaglePermit->toggle_status == 'red')
                                                            <select id="toggle_status_{{$eaglePermit->permit_id}}" class="form-control toggle_status red">
                                                                <option value="yellow">Untouched </option>
                                                                <option value="green">Major Prospect </option>
                                                                <option value="blue">Quality Prospect </option>
                                                                <option selected value="red">Active but paused </option>
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
                                                        @if ($eaglePermit->assignee == '')
                                                            <select class="form-control assignee" id="assignee_{{$eaglePermit->permit_id}}">
                                                                @else
                                                                    <select class="form-control assignee assigned_style" id="assignee_{{$eaglePermit->permit_id}}">
                                                                @endif
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
                                                <td class="text-center"><a href="{{url( 'lease-page/' . $eaglePermit->interest_area . '/' . $eaglePermit->lease_name . '/producing/' . $eaglePermit->permit_id)}}">{{$eaglePermit->lease_name}}</a></td>
                                                @else
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"><a href="{{url( 'lease-page/' . $eaglePermit->interest_area . '/' . $eaglePermit->lease_name . '/producing/' . $eaglePermit->permit_id)}}">{{$eaglePermit->lease_name}}</a></td>

                                            @endif
                                        </tr>

                                    @endforeach
                                        @endif
                                    </tbody>
                                                <tfoot>
                                                <caption class="lease_table_caption"> Eagleford Landtrac's Producing </caption>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <hr><br>
                                                                                    <!-- NON PRODUCING LEASES EAGLE -->
                                    <div class="row">
                                        <div class="offset-1 col-md-10">
                                            <table class="table table-hover table-responsive-md table-bordered non_producing_eagle_permits" id="non_producing_eagle_permits">
                                                <thead>
                                                <tr>
                                                    @if (Auth::user()->role === 'admin')
                                                        <th class="text-center">Store Lease</th>
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
                                                @if (isset($nonProducingEaglePermits) && !$nonProducingEaglePermits->isEmpty())
                                                    @foreach ($nonProducingEaglePermits as $nonProducingEaglePermit)
                                                        <?php $approvedDate = explode('T', $nonProducingEaglePermit->approved_date)?>
                                                        <input type="hidden" id="reported_operator_{{$nonProducingEaglePermit->permit_id}}" value="{{$nonProducingEaglePermit->reported_operator}}"/>

                                                        <tr class="permit_row" id="permit_row_{{$nonProducingEaglePermit->permit_id}}">

                                                            @if (Auth::user()->role === 'admin')
                                                                <td class="text-center"><button type="button" class="store_button btn btn-primary" id="store_button_{{$nonProducingEaglePermit->permit_id}}_{{$nonProducingEaglePermit->lease_name}}">Store</button></td>

                                                                <td id="id_{{$nonProducingEaglePermit->permit_id}}" class="text-center mmp-details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                                <td>
                                                                    @if ($nonProducingEaglePermit->toggle_status == 'yellow')
                                                                        <select id="toggle_status_{{$nonProducingEaglePermit->permit_id}}" class="form-control toggle_status unseen">
                                                                            <option selected value="yellow">Untouched</option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nonProducingEaglePermit->toggle_status == 'green')
                                                                        <select id="toggle_status_{{$nonProducingEaglePermit->permit_id}}" class="form-control toggle_status green">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option selected value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nonProducingEaglePermit->toggle_status == 'blue')
                                                                        <select id="toggle_status_{{$nonProducingEaglePermit->permit_id}}" class="form-control toggle_status blue">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option selected value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nonProducingEaglePermit->toggle_status == 'red')
                                                                        <select id="toggle_status_{{$nonProducingEaglePermit->permit_id}}" class="form-control toggle_status red">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option selected value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nonProducingEaglePermit->toggle_status == 'purple')
                                                                        <select id="toggle_status_{{$nonProducingEaglePermit->permit_id}}" class="form-control toggle_status purple">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option selected value="purple">Completed</option>
                                                                        </select>
                                                                    @endif

                                                                </td>
                                                                <td class="text-center">
                                                                    <select class="form-control assignee" id="assignee_{{$nonProducingEaglePermit->permit_id}}">
                                                                        <option selected value="">Select a User</option>
                                                                        @foreach ($users as $user)
                                                                            @if ($nonProducingEaglePermit->assignee == $user->id)
                                                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                                            @else
                                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="text-center">{{$nonProducingEaglePermit->county_parish}}</td>
                                                                <td class="text-center">{{$nonProducingEaglePermit->reported_operator}}</td>
                                                                <td class="text-center"><a href="{{url( 'lease-page/' . $nonProducingEaglePermit->interest_area . '/' . $nonProducingEaglePermit->lease_name . '/non-producing/' . $nonProducingEaglePermit->permit_id)}}">{{$nonProducingEaglePermit->lease_name}}</a></td>
                                                            @else
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"><a href="{{url( 'lease-page/' . $nonProducingEaglePermit->interest_area . '/' . $nonProducingEaglePermit->lease_name . '/non-producing/' . $nonProducingEaglePermit->permit_id)}}">{{$nonProducingNMPermit->lease_name}}</a></td>

                                                            @endif
                                                        </tr>

                                                    @endforeach
                                                @endif
                                                </tbody>
                                                <tfoot>
                                                <caption class="lease_table_caption">Eagleford Non-Producing Tracks</caption>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>














                        <!-- WTX INTEREST AREA -->
                        <div class="tab-pane interest_text" role="tabpanel" id="wtx_interest_area" >
                            <div class="row">
                                <div class="offset-1 col-md-10">
                                    <table class="table table-hover table-responsive-md table-bordered permit_table" id="wtx_permit_table">
                                        <thead>
                                        <tr>
                                            @if (Auth::user()->role === 'admin')

                                                <th class="text-center">Store Lease</th>
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
                                        @if (isset($wtxPermits) && !$wtxPermits->isEmpty())
                                            @foreach ($wtxPermits as $wtxPermit)
                                                <?php $approvedDate = explode('T', $wtxPermit->approved_date)?>
                                                <input type="hidden" id="reported_operator_{{$wtxPermit->permit_id}}" value="{{$wtxPermit->reported_operator}}"/>

                                                <tr class="permit_row" id="permit_row_{{$wtxPermit->permit_id}}">

                                                    @if (Auth::user()->role === 'admin')
                                                        <td class="text-center"><button type="button" class="store_button btn btn-primary" id="store_button_{{$wtxPermit->permit_id}}_{{$wtxPermit->lease_name}}">Store</button></td>
                                                        <td id="id_{{$wtxPermit->permit_id}}" class="text-center mmp-details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                        <td>
                                                            @if ($wtxPermit->toggle_status == 'yellow')
                                                                <select id="toggle_status_{{$wtxPermit->permit_id}}" class="form-control toggle_status unseen">
                                                                    <option selected value="yellow">Untouched</option>
                                                                    <option value="green">Major Prospect </option>
                                                                    <option value="blue">Quality Prospect </option>
                                                                    <option value="red">Active but paused </option>
                                                                    <option value="purple">Completed</option>

                                                                </select>
                                                            @elseif ($wtxPermit->toggle_status == 'green')
                                                                <select id="toggle_status_{{$wtxPermit->permit_id}}" class="form-control toggle_status green">
                                                                    <option value="yellow">Untouched </option>
                                                                    <option selected value="green">Major Prospect </option>
                                                                    <option value="blue">Quality Prospect </option>
                                                                    <option value="red">Active but paused </option>
                                                                    <option value="purple">Completed</option>
                                                                </select>
                                                            @elseif ($wtxPermit->toggle_status == 'blue')
                                                                <select id="toggle_status_{{$wtxPermit->permit_id}}" class="form-control toggle_status blue">
                                                                    <option value="yellow">Untouched </option>
                                                                    <option value="green">Major Prospect </option>
                                                                    <option selected value="blue">Quality Prospect </option>
                                                                    <option value="red">Active but paused </option>
                                                                    <option value="purple">Completed</option>
                                                                </select>
                                                            @elseif ($wtxPermit->toggle_status == 'red')
                                                                <select id="toggle_status_{{$wtxPermit->permit_id}}" class="form-control toggle_status red">
                                                                    <option value="yellow">Untouched </option>
                                                                    <option value="green">Major Prospect </option>
                                                                    <option value="blue">Quality Prospect </option>
                                                                    <option selected value="red">Active but paused </option>
                                                                    <option value="purple">Completed</option>
                                                                </select>
                                                            @elseif ($wtxPermit->toggle_status == 'purple')
                                                                <select id="toggle_status_{{$wtxPermit->permit_id}}" class="form-control toggle_status purple">
                                                                    <option value="yellow">Untouched </option>
                                                                    <option value="green">Major Prospect </option>
                                                                    <option value="blue">Quality Prospect </option>
                                                                    <option value="red">Active but paused </option>
                                                                    <option selected value="purple">Completed</option>
                                                                </select>
                                                            @endif

                                                        </td>
                                                        <td class="text-center">
                                                            <select class="form-control assignee" id="assignee_{{$wtxPermit->permit_id}}">
                                                                <option selected value="">Select a User</option>
                                                                @foreach ($users as $user)
                                                                    @if ($wtxPermit->assignee == $user->id)
                                                                        <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                                    @else
                                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center">{{$wtxPermit->county_parish}}</td>
                                                        <td class="text-center">{{$wtxPermit->reported_operator}}</td>
                                                        <td class="text-center"><a href="{{url( 'lease-page/' . $wtxPermit->interest_area . '/' . $wtxPermit->lease_name . '/producing/' . $wtxPermit->permit_id)}}">{{$wtxPermit->lease_name}}</a></td>
                                                    @else
                                                        <td class="text-center"></td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"><a href="{{url( 'lease-page/' . $wtxPermit->interest_area . '/' . $wtxPermit->lease_name . '/producing/' . $wtxPermit->permit_id)}}">{{$wtxPermit->lease_name}}</a></td>

                                                    @endif
                                                </tr>

                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <caption class="lease_table_caption">WTX Landtrac's Producing </caption>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <hr><br>



                                                                            <!-- NON PRODUCING LEASES WTX -->
                            <div class="row">
                                <div class="offset-1 col-md-10">
                                    <table class="table table-hover table-responsive-md table-bordered non_producing_wtx_permits" id="non_producing_wtx_permits">
                                        <thead>
                                        <tr>
                                            @if (Auth::user()->role === 'admin')
                                                <th class="text-center">Store Lease</th>
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
                                        @if (isset($nonProducingWTXPermits) && !$nonProducingWTXPermits->isEmpty())
                                            @foreach ($nonProducingWTXPermits as $nonProducingWTXPermit)
                                                <?php $approvedDate = explode('T', $nonProducingWTXPermit->approved_date)?>
                                                <input type="hidden" id="reported_operator_{{$nonProducingWTXPermit->permit_id}}" value="{{$nonProducingWTXPermit->reported_operator}}"/>

                                                <tr class="permit_row" id="permit_row_{{$nonProducingWTXPermit->permit_id}}">

                                                    @if (Auth::user()->role === 'admin')
                                                        <td class="text-center"><button type="button" class="store_button btn btn-primary" id="store_button_{{$nonProducingWTXPermit->permit_id}}_{{$nonProducingWTXPermit->lease_name}}">Store</button></td>

                                                        <td id="id_{{$nonProducingWTXPermit->permit_id}}" class="text-center mmp-details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                        <td>
                                                            @if ($nonProducingWTXPermit->toggle_status == 'yellow')
                                                                <select id="toggle_status_{{$nonProducingWTXPermit->permit_id}}" class="form-control toggle_status unseen">
                                                                    <option selected value="yellow">Untouched</option>
                                                                    <option value="green">Major Prospect </option>
                                                                    <option value="blue">Quality Prospect </option>
                                                                    <option value="red">Active but paused </option>
                                                                    <option value="purple">Completed</option>
                                                                </select>
                                                            @elseif ($nonProducingWTXPermit->toggle_status == 'green')
                                                                <select id="toggle_status_{{$nonProducingWTXPermit->permit_id}}" class="form-control toggle_status green">
                                                                    <option value="yellow">Untouched </option>
                                                                    <option selected value="green">Major Prospect </option>
                                                                    <option value="blue">Quality Prospect </option>
                                                                    <option value="red">Active but paused </option>
                                                                    <option value="purple">Completed</option>
                                                                </select>
                                                            @elseif ($nonProducingWTXPermit->toggle_status == 'blue')
                                                                <select id="toggle_status_{{$nonProducingWTXPermit->permit_id}}" class="form-control toggle_status blue">
                                                                    <option value="yellow">Untouched </option>
                                                                    <option value="green">Major Prospect </option>
                                                                    <option selected value="blue">Quality Prospect </option>
                                                                    <option value="red">Active but paused </option>
                                                                    <option value="purple">Completed</option>
                                                                </select>
                                                            @elseif ($nonProducingWTXPermit->toggle_status == 'red')
                                                                <select id="toggle_status_{{$nonProducingWTXPermit->permit_id}}" class="form-control toggle_status red">
                                                                    <option value="yellow">Untouched </option>
                                                                    <option value="green">Major Prospect </option>
                                                                    <option value="blue">Quality Prospect </option>
                                                                    <option selected value="red">Active but paused </option>
                                                                    <option value="purple">Completed</option>
                                                                </select>
                                                            @elseif ($nonProducingWTXPermit->toggle_status == 'purple')
                                                                <select id="toggle_status_{{$nonProducingWTXPermit->permit_id}}" class="form-control toggle_status purple">
                                                                    <option value="yellow">Untouched </option>
                                                                    <option value="green">Major Prospect </option>
                                                                    <option value="blue">Quality Prospect </option>
                                                                    <option value="red">Active but paused </option>
                                                                    <option selected value="purple">Completed</option>
                                                                </select>
                                                            @endif

                                                        </td>
                                                        <td class="text-center">
                                                            <select class="form-control assignee" id="assignee_{{$nonProducingWTXPermit->permit_id}}">
                                                                <option selected value="">Select a User</option>
                                                                @foreach ($users as $user)
                                                                    @if ($nonProducingWTXPermit->assignee == $user->id)
                                                                        <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                                    @else
                                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center">{{$nonProducingWTXPermit->county_parish}}</td>
                                                        <td class="text-center">{{$nonProducingWTXPermit->reported_operator}}</td>
                                                        <td class="text-center"><a href="{{url( 'lease-page/' . $nonProducingWTXPermit->interest_area . '/' . $nonProducingWTXPermit->lease_name . '/non-producing/' . $nonProducingWTXPermit->permit_id)}}">{{$nonProducingWTXPermit->lease_name}}</a></td>
                                                    @else
                                                        <td class="text-center"></td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center"><a href="{{url( 'lease-page/' . $nonProducingWTXPermit->interest_area . '/' . $nonProducingWTXPermit->lease_name . '/non-producing/' . $nonProducingWTXPermit->permit_id)}}">{{$nonProducingWTXPermit->lease_name}}</a></td>

                                                    @endif
                                                </tr>

                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <caption class="lease_table_caption">WTX Non-Producing Tracks</caption>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>






















                                <!-- NM INTEREST AREA -->
                                <div class="tab-pane interest_text" role="tabpanel" id="nm_interest_area" >
                                    <div class="row">
                                        <div class="offset-1 col-md-10">
                                            <table class="table table-hover table-responsive-md table-bordered permit_table" id="nm_permit_table">
                                                <thead>
                                                <tr>
                                                    @if (Auth::user()->role === 'admin')

                                                        <th class="text-center">Store Lease</th>
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
                                                @if (isset($nmPermits) && !$nmPermits->isEmpty())
                                                    @foreach ($nmPermits as $nmPermit)
                                                        <?php $approvedDate = explode('T', $nmPermit->approved_date)?>
                                                        <input type="hidden" id="reported_operator_{{$nmPermit->permit_id}}" value="{{$nmPermit->reported_operator}}"/>

                                                        <tr class="permit_row" id="permit_row_{{$nmPermit->permit_id}}">

                                                            @if (Auth::user()->role === 'admin')
                                                                <td class="text-center"><button type="button" class="store_button btn btn-primary" id="store_button_{{$nmPermit->permit_id}}_{{$nmPermit->lease_name}}">Store</button></td>
                                                                <td id="id_{{$nmPermit->permit_id}}" class="text-center mmp-details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                                <td>
                                                                    @if ($nmPermit->toggle_status == 'yellow')
                                                                        <select id="toggle_status_{{$nmPermit->permit_id}}" class="form-control toggle_status unseen">
                                                                            <option selected value="yellow">Untouched</option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>

                                                                        </select>
                                                                    @elseif ($nmPermit->toggle_status == 'green')
                                                                        <select id="toggle_status_{{$nmPermit->permit_id}}" class="form-control toggle_status green">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option selected value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nmPermit->toggle_status == 'blue')
                                                                        <select id="toggle_status_{{$nmPermit->permit_id}}" class="form-control toggle_status blue">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option selected value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nmPermit->toggle_status == 'red')
                                                                        <select id="toggle_status_{{$nmPermit->permit_id}}" class="form-control toggle_status red">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option selected value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nmPermit->toggle_status == 'purple')
                                                                        <select id="toggle_status_{{$nmPermit->permit_id}}" class="form-control toggle_status purple">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option selected value="purple">Completed</option>
                                                                        </select>
                                                                    @endif

                                                                </td>
                                                                <td class="text-center">
                                                                    <select class="form-control assignee" id="assignee_{{$nmPermit->permit_id}}">
                                                                        <option selected value="">Select a User</option>
                                                                        @foreach ($users as $user)
                                                                            @if ($nmPermit->assignee == $user->id)
                                                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                                            @else
                                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="text-center">{{$nmPermit->county_parish}}</td>
                                                                <td class="text-center">{{$nmPermit->reported_operator}}</td>
                                                                <td class="text-center"><a href="{{url( 'lease-page/' . $nmPermit->interest_area . '/' . $nmPermit->lease_name . '/producing/' . $nmPermit->permit_id)}}">{{$nmPermit->lease_name}}</a></td>
                                                            @else
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"><a href="{{url( 'lease-page/' . $nmPermit->interest_area . '/' . $nmPermit->lease_name . '/producing/' . $nmPermit->permit_id)}}">{{$nmPermit->lease_name}}</a></td>

                                                            @endif
                                                        </tr>

                                                    @endforeach
                                                @endif
                                                </tbody>
                                                <tfoot>
                                                <caption class="lease_table_caption">NM Landtrac's Producing </caption>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>



                                    <hr><br>
                                                                            <!-- NON PRODUCING LEASES NM -->
                                    <div class="row">
                                        <div class="offset-1 col-md-10">
                                            <table class="table table-hover table-responsive-md table-bordered non_producing_nm_permits" id="non_producing_nm_permits">
                                                <thead>
                                                <tr>
                                                    @if (Auth::user()->role === 'admin')
                                                        <th class="text-center">Store Lease</th>
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
                                                @if (isset($nonProducingNMPermits) && !$nonProducingNMPermits->isEmpty())
                                                    @foreach ($nonProducingNMPermits as $nonProducingNMPermit)
                                                        <?php $approvedDate = explode('T', $nonProducingNMPermit->approved_date)?>
                                                        <input type="hidden" id="reported_operator_{{$nonProducingNMPermit->permit_id}}" value="{{$nonProducingNMPermit->reported_operator}}"/>

                                                        <tr class="permit_row" id="permit_row_{{$nonProducingNMPermit->permit_id}}">

                                                            @if (Auth::user()->role === 'admin')
                                                                <td class="text-center"><button type="button" class="store_button btn btn-primary" id="store_button_{{$nonProducingNMPermit->permit_id}}_{{$nonProducingNMPermit->lease_name}}">Store</button></td>

                                                                <td id="id_{{$nonProducingNMPermit->permit_id}}" class="text-center mmp-details-control"><i style="cursor:pointer;" class="far fa-dot-circle"></i></td>
                                                                <td>
                                                                    @if ($nonProducingNMPermit->toggle_status == 'yellow')
                                                                        <select id="toggle_status_{{$nonProducingNMPermit->permit_id}}" class="form-control toggle_status unseen">
                                                                            <option selected value="yellow">Untouched</option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nonProducingNMPermit->toggle_status == 'green')
                                                                        <select id="toggle_status_{{$nonProducingNMPermit->permit_id}}" class="form-control toggle_status green">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option selected value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nonProducingNMPermit->toggle_status == 'blue')
                                                                        <select id="toggle_status_{{$nonProducingNMPermit->permit_id}}" class="form-control toggle_status blue">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option selected value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nonProducingNMPermit->toggle_status == 'red')
                                                                        <select id="toggle_status_{{$nonProducingNMPermit->permit_id}}" class="form-control toggle_status red">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option selected value="red">Active but paused </option>
                                                                            <option value="purple">Completed</option>
                                                                        </select>
                                                                    @elseif ($nonProducingNMPermit->toggle_status == 'purple')
                                                                        <select id="toggle_status_{{$nonProducingNMPermit->permit_id}}" class="form-control toggle_status purple">
                                                                            <option value="yellow">Untouched </option>
                                                                            <option value="green">Major Prospect </option>
                                                                            <option value="blue">Quality Prospect </option>
                                                                            <option value="red">Active but paused </option>
                                                                            <option selected value="purple">Completed</option>
                                                                        </select>
                                                                    @endif

                                                                </td>
                                                                <td class="text-center">
                                                                    <select class="form-control assignee" id="assignee_{{$nonProducingNMPermit->permit_id}}">
                                                                        <option selected value="">Select a User</option>
                                                                        @foreach ($users as $user)
                                                                            @if ($nonProducingNMPermit->assignee == $user->id)
                                                                                <option selected value="{{$user->id}}">{{$user->name}}</option>
                                                                            @else
                                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="text-center">{{$nonProducingNMPermit->county_parish}}</td>
                                                                <td class="text-center">{{$nonProducingNMPermit->reported_operator}}</td>
                                                                <td class="text-center"><a href="{{url( 'lease-page/' . $nonProducingNMPermit->interest_area . '/' . $nonProducingNMPermit->lease_name . '/non-producing/' . $nonProducingNMPermit->permit_id)}}">{{$nonProducingNMPermit->lease_name}}</a></td>
                                                            @else
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"></td>
                                                                <td class="text-center"><a href="{{url( 'lease-page/' . $nonProducingNMPermit->interest_area . '/' . $nonProducingNMPermit->lease_name . '/non-producing/' . $nonProducingNMPermit->permit_id)}}">{{$nonProducingNMPermit->lease_name}}</a></td>

                                                            @endif
                                                        </tr>

                                                    @endforeach
                                                @endif
                                                </tbody>
                                                <tfoot>
                                                <caption class="lease_table_caption">NM Non-Producing Tracks</caption>
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
