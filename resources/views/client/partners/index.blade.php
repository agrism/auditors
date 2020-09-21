@extends('client.layout.master') 

@section('content')

<div class="col-lg-12"> 
	<div class="panel panel-default">
		<div class="panel-heading">
			Partners list in system <a href="{{ url(route('client.partners.create'))}}"><div class="btn btn-success btn-xs fa-plus fa"></div></a>
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
							<th>action</th>

						</tr>
					</thead>
					<tbody>
						@foreach($partners as $partner)
						<tr>
							<td>{{ $partner->id}}</td>
							<td>{{ $partner->name}}</td>
							<td>{{ $partner->registration_number}}</td>
							<td>
								<a href="{{ url(route('client.partners.show', $partner->id))}}"><div class="btn btn-info btn-xs fa   fa-info"></div></a>

								<a href="{{ url(route('client.partners.edit',  $partner->id))}}"><div class="btn btn-success btn-xs fa-edit fa"></div></a>
								<a href="{{ url(route('client.partners.delete',  [$partner->id]))}}"><div class="btn btn-danger btn-xs fa-remove fa"></div></a>

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