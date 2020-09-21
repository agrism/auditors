@extends('admin.layout.admin')

@section('content')
    <div class="panel panel-primary">
        <div class="panel-heading">NPI (acc 2410)</div>
        <div class="panel-body">

            {{Form::model('data', ['method'=>'post', 'route'=>'admin.npi.handle', 'class'=>'form-horizontal'])}}

            <div class="form-group">
                <label for="from" class="col-md-2 control-label">Company</label>
                <div class="col-md-10">
                    {{Form::text('company', request()->get('company'), ['class'=>'form-control col-md-10', 'placeholder'=>_('company')])}}
                </div>
            </div>
            <div class="form-group">
                <label for="from" class="col-md-2 control-label">Description</label>
                <div class="col-md-10">
                    {{Form::text('description', request()->get('description'), ['class'=>'form-control col-md-10', 'placeholder'=>_('description')])}}
                </div>
            </div>
            <div class="form-group">
                <label for="from" class="col-md-2 control-label">Invoice No</label>
                <div class="col-md-10">
                    {{Form::text('invoice_no', request()->get('invoice_no'), ['class'=>'form-control col-md-10','placeholder'=>_('invoice no')])}}
                </div>
            </div>
            <div class="form-group">
                <label for="from" class="col-md-2 control-label">Invoice Amount EUR</label>
                <div class="col-md-10">
                    {{Form::text('invoice_amount', request()->get('invoice_amount'), ['class'=>'form-control col-md-10', 'placeholder'=>_('invoice amount')])}}
                </div>
            </div>
            <div class="form-group">
                <label for="from" class="col-md-2 control-label">Invoice date</label>
                <div class="col-md-10">
                    {{Form::text('invoice_date', request()->get('invoice_date'), ['class'=>'form-control col-md-10','id'=>'dp0', 'placeholder'=>_('invoice date')])}}
                </div>
            </div>
            <div class="form-group">
                <label for="from" class="col-md-2 control-label">Period from</label>
                <div class="col-md-10">
                    {{Form::text('period_from', request()->get('invoice_date'), ['class'=>'form-control col-md-10', 'id'=>'dp1', 'placeholder'=>_('from')])}}
                </div>
            </div>
            <div class="form-group">
                <label for="from" class="col-md-2 control-label">Period till</label>
                <div class="col-md-10">
                    {{Form::text('period_till', null, ['class'=>'form-control col-md-10', 'id'=>'dp2', 'placeholder'=>_('till')])}}
                </div>
            </div>

            <div class="form-group">
                <label for="from" class="col-md-2 control-label">Report date</label>
                <div class="col-md-10">
                    {{Form::text('report_date', null, ['class'=>'form-control col-md-10','id'=>'dp3', 'placeholder'=>_('report date')])}}
                </div>
            </div>

            <div class="form-group">
                <label for="from" class="col-md-2 control-label"></label>
                <div class="col-md-10">
                    {{Form::submit(_('Calculate NPI xml'), ['class'=>'form-control btn btn-info'])}}
                </div>
            </div>

            @if(isset($dataArray) && is_array($dataArray) && count($dataArray) > 0 )


                <div class="form-group">
                    <label for="from" class="col-md-2 control-label"></label>
                    <div class="col-md-10">
                        @if($fileName)

                            <div style="border: red solid 2px;padding: 15px; border-radius: 8px">
                                <a href="/{{$fileName}}" class="">{{$fileName}}</a>
                            </div>
                        @else

                            {{Form::submit(_('Generate NPI xml'), ['class'=>'form-control btn btn-info', 'name'=>'submitValue', 'value'=>'Generate NPI xml'])}}

                        @endif


                    </div>
                </div>




            @endif

            {{Form::close()}}

        </div>
        <div class="panel-footer">


        </div>
    </div>



    <div class="panel panel-success">
        <div class="panel-heading">Result</div>
        <div class="panel-body">

            <table class="table">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Partneris</th>
                    <th>Doc Number</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Days</th>
                </tr>
                </thead>
                <tbody>

                @foreach(isset($dataArray) && is_array($dataArray) ? $dataArray : [] as $data)
                    <tr>
                        <td>{{$data['date']}}</td>
                        <td>{{$data['partner_name']}}</td>
                        <td>{{$data['number']}}</td>
                        <td>{{$data['comment']}}</td>
                        <td>{{$data['amount']}}</td>
                        <td>{{$data['days']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        <div class="panel-footer">&copy;</div>
    </div>



@endsection


@section('js')
    <script type="text/javascript">
		$(document).ready(function () {

			$('#dp0').datepicker({
				format: 'dd.mm.yyyy',
				weekStart: 1,
				todayBtn: "linked",
				todayHighlight: true,
				autoclose: true,
				//                calendarWeeks: true,
				daysOfWeekDisabled: [],
				daysOfWeekHighlighted: [0, 6]

			});

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

			$('#dp3').datepicker({
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
