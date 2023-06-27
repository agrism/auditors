@extends('admin.layout.admin')

@section('content')

    <div class="col-md-12">
        <h3>VAT return</h3>
        <form action="{{route('admin.vat.handle')}}" method="post">
            @csrf

            <div class="form-group col-ms-12">

                <select name="company_id" class="form-control">
                    @foreach($companies as $company)
                        <option value="{{$company->id}}" @if(($data['company_id'] ?? null) == $company->id) selected @endif>{{$company->title}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-ms-12 row">
                <div class="col-md-6">
                    <label for="from">From</label>
                    {{Form::text('from', isset($data['from']) ? $data['from'] : null, ['class'=>'form-control', 'id'=>'dp1', 'placeholder'=>_('data from'), 'autocomplete'=>'off'])}}
                </div>
                <div class="col-md-6">
                    <label for="to">To</label>
                    {{Form::text('to', isset($data['to']) ? $data['to'] : null, ['class'=>'form-control', 'id'=>'dp2', 'placeholder'=>_('data to'), 'autocomplete'=>'off'])}}
                </div>
            </div>


            <div class="form-group col-ms-12">
                <label for="to">Payable</label>
                {{Form::text('payable', isset($data['payable']) ? $data['payable'] : null, ['class'=>'form-control', 'id'=>'payable', 'placeholder'=>_('how much wish to pay'), 'autocomplete'=>'off'])}}

            </div>

            <div class="form-group col-ms-12">
                <button class="btn btn-default">Submit</button>
            </div>
        </form>

        <pre>
        file location: <a href="{{url('test.xml')}}" target="_blank">{{url('test.xml')}}</a>

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