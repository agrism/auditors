@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    {!! Form::label('title', 'Title', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('title', isset($company) ? $company['title'] : null , ['class'=>'form-control', 'placeholder'=>'Input title'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('registration_number', 'Reg.No', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('registration_number', isset($company) ? $company['registration_number'] : null , ['class'=>'form-control', 'placeholder'=>'Input Reg.no'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('closed_data_date', 'Closed data date', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('closed_data_date', isset($company) ? $company['closed_data_date'] : null , ['class'=>'form-control', 'placeholder'=>'Input date', 'id'=>'dp', 'readonly'] ) !!}
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function () {
            $('#dp').datepicker({
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
//                calendarWeeks: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],

            });
        });

    </script>
@endsection