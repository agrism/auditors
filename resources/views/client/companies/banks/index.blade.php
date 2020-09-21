@extends('client.layout.master') 

@section('content')

<div class="col-lg-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			Optional receivers of incoming payments <a href="{{ url(route('client.companies.bank.create'))}}"><div class="btn btn-success btn-xs fa-plus fa"></div></a>
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Bank</th>
							<th>action</th>

						</tr>
					</thead>
					<tbody>
						@foreach($banks as $bank)
						<tr>
							<td>{{ $bank->id}}</td>
							<td>{{ $bank->payment_receiver}}</td>
							<td>{{ $bank->bank}}</td>
							<td>
								{{-- <a href="{{ url(route('client.companies.bank.show', $bank->id))}}"><div class="btn btn-info btn-xs fa   fa-info"></div></a> --}}

								<a href="{{ url(route('client.companies.bank.edit',  $bank->id))}}"><div class="btn btn-success btn-xs fa-edit fa"></div></a>
								
								<a href="{{ url(route('client.companies.bank.destroy',  [$bank->id,'method'=>'delete']))}}"><div class="btn btn-danger btn-xs fa-remove fa"></div></a>

							</td>

						</tr>
						@endforeach

					</tbody>
				</table>
			</div>
			<!-- /.table-responsive -->
		</div>
		<!-- /.panel-body -->
	</div>
	<!-- /.panel -->
</div>
@stop