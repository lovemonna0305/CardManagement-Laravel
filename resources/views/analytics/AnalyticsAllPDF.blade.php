{{--<!doctype html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--<meta charset="UTF-8">--}}
{{--<meta name="viewport"--}}
{{--content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">--}}
{{--<meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
{{--<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css ')}}">--}}
{{--<!-- Font Awesome -->--}}
{{--<link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css')}} ">--}}
{{--<!-- Ionicons -->--}}
{{--<link rel="stylesheet" href="{{ asset('assets/bower_components/Ionicons/css/ionicons.min.css')}} ">--}}

{{--<title>Card Analytics Exports All PDF</title>--}}
{{--</head>--}}
{{--<body>--}}
<style>
    body {
        padding: 15px;
    }

    #categories {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #categories td, #categories th {
        border: 1px solid #ddd;
        padding: 4px;
        text-align: center;
        font-size: 10px;
    }

    #categories tr:nth-child(even){background-color: #f2f2f2;}

    #categories tr:hover {background-color: #ddd;}

    #categories th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: center;
        background-color: #4CAF50;
        color: white;
    }
    .title {
        /* display: flex; */
        font-size: 20px ;
        color: red;
        font-weight: bold;
        gap: 30px;
        align-items: center;
    }
    .title  span {
        margin: 0 10px 0 10px;
    }
    .title p {
        margin: 0;
    }
    .content div{
        padding: 0 20px;
    }
    .content p {
        font-size: 16px ;
        font-weight: 600;
    }
</style>

<div class="title">
    <p>Card number: <span>{{ $card_number}}</span> Analytics</p>
    <!-- <p style="margin: 0;">Analytics</p>     -->
</div>
<div class="content">
    <p>Non Working days:</p>
    <div>
        <table id="categories">
            <thead>
                @foreach($non_working_days as $non_working_day)
                <tr>
                    <td>{{$non_working_day->working_day}}</td>
                    <td>{{$non_working_day->working_hour}}</td>
                    <td>{{$non_working_day->load}}</td>
                    <td>{{$non_working_day->rate}}</td>
                    <td>{{$non_working_day->type}}</td>
                    <td>{{$non_working_day->of_rate}}</td>
                    <td>{{$non_working_day->operator}}</td>
                    <td>{{$non_working_day->CCIT}}</td>
                    <td>{{$non_working_day->bus_line}}</td>
                    <td>{{$non_working_day->used_value}}</td>
                </tr>
                @endforeach
            </thead>
        </table>
    </div>
    <p>Total Spent: <span >{{$non_working_day_sum}}</span> (sum of them)</p>
</div>
<div class="content">
    <p>Non Working Hours:</p>
    <div>
        <table id="categories">
            <thead>
                @foreach($non_working_hours as $non_working_hour)
                <tr>
                    <td>{{$non_working_hour->working_hour}}</td>
                    <td>{{$non_working_hour->working_hour}}</td>
                    <td>{{$non_working_hour->load}}</td>
                    <td>{{$non_working_hour->rate}}</td>
                    <td>{{$non_working_hour->type}}</td>
                    <td>{{$non_working_hour->of_rate}}</td>
                    <td>{{$non_working_hour->operator}}</td>
                    <td>{{$non_working_hour->CCIT}}</td>
                    <td>{{$non_working_hour->bus_line}}</td>
                    <td>{{$non_working_hour->used_value}}</td>
                </tr>
                @endforeach
            </thead>
        </table>
    </div>
    <p>Total Spent: <span >{{$non_working_hour_sum}}</span> (sum of them)</p>
</div>
<div class="content">
    <p class="subtitle">Non Bus Lines:</p>
    <div>
        <table id="categories">
            <thead>
                @foreach($non_bus_lines as $non_bus_line)
                <tr>
                    <td>{{$non_bus_line->bus_line}}</td>
                    <td>{{$non_bus_line->bus_line}}</td>
                    <td>{{$non_bus_line->load}}</td>
                    <td>{{$non_bus_line->rate}}</td>
                    <td>{{$non_bus_line->type}}</td>
                    <td>{{$non_bus_line->of_rate}}</td>
                    <td>{{$non_bus_line->operator}}</td>
                    <td>{{$non_bus_line->CCIT}}</td>
                    <td>{{$non_bus_line->bus_line}}</td>
                    <td>{{$non_bus_line->used_value}}</td>
                </tr>
                @endforeach
            </thead>
        </table>
    </div>
    <p>Total Spent: <span >{{$non_bus_line_sum}}</span> (sum of them)</p>
</div>


{{--<!-- jQuery 3 -->--}}
{{--<script src="{{  asset('assets/bower_components/jquery/dist/jquery.min.js') }} "></script>--}}
{{--<!-- Bootstrap 3.3.7 -->--}}
{{--<script src="{{  asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }} "></script>--}}
{{--<!-- AdminLTE App -->--}}
{{--<script src="{{  asset('assets/dist/js/adminlte.min.js') }}"></script>--}}
{{--</body>--}}
{{--</html>--}}


