@extends('admin.layout.admin')

@section('content')

<div class="col-lg-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			User <b>{{ $user->name }}</b> company list:

			{{-- 			<a href="{{ url(route('admin.users.create'))}}"><div class="btn btn-success btn-xs fa-plus fa"></div></a> --}}
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<div class="table-responsive">

				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Company Title</th>
							<th>Reg. No</th>
							<th>Assign to User {{ $user->name }}</th>

						</tr>
					</thead>
					{!! Form::open(['class'=>'form-horizontal', 'method'=>'put', 'route'=>['admin.users.companies.update', $user->id]]) !!}
					<tbody>
						@foreach($companies as $company)
						<tr>
							<td>{{ $company->id }}</td>
							<td>{{ $company->title }}</td>
							<td>{{ $company->registration_number }}</td>
							<td>


								{!! Form::checkbox('company_id[]', $company->id, $company->users->count() >0 ? true: false) !!}
{{-- 								<a href="{{ url(route('admin.users.companies.show', $user->id))}}"><div class="btn btn-info btn-xs fa   fa-compress"></div></a>
								<a href="{{ url(route('admin.users.edit',  $user->id))}}"><div class="btn btn-success btn-xs fa-edit fa"></div></a>
								<a href="{{ url(route('admin.users.destroy',  [$user->id,'method'=>'delete']))}}"><div class="btn btn-danger btn-xs fa-remove fa"></div></a> --}}

							</td>

						</tr>
						@endforeach

					</tbody>
				</table>



				{!! Form::submit('Update')!!}
				{!! Form::close() !!}
			</div>
			<!-- /.table-responsive -->
		</div>
		<!-- /.panel-body -->
	</div>
	<!-- /.panel -->
</div>




@stop

@section('sidebar')


@stop