@extends('admin.layout.admin')

@section('content')

    <div class="col-md-12">
        <h3>Vacations</h3>
        <form action="{{route('admin.vacations.handle')}}" method="post">
            @csrf

            <div class="form-group col-ms-12">

                <select name="employee_id" class="form-control">
                    @foreach($employees as $employee)
                        <option value="{{$employee->id}}" @if($employee->active) selected @endif>{{$employee->name}}</option>
                    @endforeach
                </select>
            </div>


            <div class="form-group col-ms-12">
                <textarea name="data" id="" cols="30" rows="10" class="form-control">
                    {{request()->get('data')}}
                </textarea>
            </div>
            <div class="form-group col-ms-12">
                <button class="btn btn-default">Submit</button>
            </div>
        </form>

        <table class="table">
            <thead>
            <th>period</th>
            <th>working days</th>
            <th>from</th>
            <th>to</th>
            <th>holidays</th>
            <th>total days</th>
            </thead>
            <tbody>
            @foreach($data as $period => $periodData)
                @foreach($periodData as $monthItem)

                    @if(!isset($monthItem['working-days']))
                        @continue
                    @endif

                    <tr>
                        <td>{{$period}}</td>
                        <td>{{$monthItem['working-days']}}</td>
                        <td>{{$monthItem['from']}}</td>
                        <td>{{$monthItem['to']}}</td>
                        <td>{{$monthItem['holidays']}}</td>
                        <td>{{$monthItem['days']}}</td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
            <thead>
            <tr>
                <th></th>
                <th>{{$data['total']['working-days'] ?? '-'}}</th>
                <th></th>
                <th></th>
                <th>{{$data['total']['holidays'] ?? '-'}}</th>
                <th>{{$data['total']['days'] ?? '-'}}</th>
            </tr>
            </thead>
        </table>


    </div>
@stop


@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            console.log('11111');
            $('#dp1').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                //                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6]

            });

            $('#dp2').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                //                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6]

            });
        });

    </script>
@stop