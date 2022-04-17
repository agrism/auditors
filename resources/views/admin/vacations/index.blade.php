@extends('admin.layout.admin')

@section('content')

    <div class="col-md-12">
        <h3>Vacations</h3>
        <form action="{{route('admin.vacations.handle')}}" method="post"
              enctype="multipart/form-data"
        >
            @csrf

            <div class="form-group col-ms-12">

                <select name="employee_id" class="form-control">
                    @foreach($employees as $employee)
                        <option value="{{$employee->id}}" @if($employee->active) selected @endif>{{$employee->name}}</option>
                    @endforeach
                </select>
            </div>


            <div class="form-group col-ms-12">

                <input type="file" name="data" class="form-control" value="{{request()->data}}">

            </div>
            <div class="form-group col-ms-12">
                <button class="btn btn-default">Submit</button>
            </div>
        </form>

        <table class="table">
            <thead>
            <th>date used</th>
            <th>working days used</th>
            <th>date earned</th>
            <th>working days earned</th>
            <th>working days accumulated</th>
            </thead>
            <tbody>
            @foreach($data['items'] ?? [] as $item)
                <tr>
                    <td>{{$item->usedDate}}</td>
                    <td>{{$item->usedDays}}</td>
                    <td>{{$item->earnedDate}}</td>
                    <td>{{$item->earnedDays}}</td>
                    <td>{{$item->accumulatedDays}}</td>
                </tr>
            @endforeach
            </tbody>
            <thead>
            <th>-</th>
            <th>{{$data['totalUsed'] ?? '-'}}</th>
            <th>-</th>
            <th>{{$data['totalEarned'] ?? '-'}}</th>
            <th>-</th>
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