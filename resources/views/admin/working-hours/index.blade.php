@extends('admin.layout.admin')

@section('content')

    <div class="col-md-12">
{{--    <pre>--}}
{{--	{{var_dump($data)}}--}}
{{--	</pre>--}}

        {{Form::model('data', ['method'=>'post', 'route'=>'admin.working-hours.handle'])}}


        <div class="form-group col-ms-12">
            <div class="col-md-6">
                <label for="from">Year</label>
                {!! Form::select('year', $years , $selectedYear, ['class' => 'form-control']) !!}
            </div>
            <div class="col-md-6">
                <label for="to">Month</label>
                {!! Form::select('month', $months , $selectedMonth, ['class' => 'form-control']) !!}
            </div>


        </div>


        <div class="form-group col-md-12">
            <label for="company_id">Company</label>
            {{Form::select('company_id', $companies->pluck('title', 'id'), isset($data['company_id']) ? $data['company_id'] : null, ['class'=>'form-control'] )}}
        </div>

        <div class="form-group col-md-3">
            <label for="company_id"></label>
            {{Form::submit(_('Create export file'), ['class'=>'form-control btn btn-info'])}}
        </div>

        {{Form::close()}}

        <pre>
        file location: <a href="{{url('test.xml')}}" target="_blank">{{url('test.xml')}}</a>
    </pre>
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

