@extends('admin.layout.admin') 

@section('content')

<div class="col-lg-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			Companies list in system <a href="{{ url(route('admin.companies.create'))}}"><div class="btn btn-success btn-xs fa-plus fa"></div></a>
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Reg.No</th>
							<th>Closed data</th>
							<th>action</th>

						</tr>
					</thead>
					<tbody>
						@foreach($companies as $company)
						<tr>
							<td>{{ $company->id}}</td>
							<td>{{ $company->title}}</td>
							<td>{{ $company->registration_number}}</td>
							<td>{{ isset($company->closed_data_date) ? $company->closed_data_date : '-'}}</td>
							<td>
								<a href="{{ url(route('admin.companies.users.show', $company->id))}}"><div class="btn btn-info btn-xs fa   fa-compress"></div></a>

								<a href="{{ url(route('admin.companies.edit',  $company->id))}}"><div class="btn btn-success btn-xs fa-edit fa"></div></a>
								<a href="{{ url(route('admin.companies.show',  $company->id))}}"><div class="btn btn-success btn-xs fa-info fa"></div></a>
								<a href="{{ url(route('admin.companies.destroy',  [$company->id,'method'=>'delete']))}}"><div class="btn btn-danger btn-xs fa-remove fa"></div></a>

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