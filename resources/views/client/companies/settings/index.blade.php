@extends('client.layout.master')

@section('content')

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                System settings
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    {!! Form::open(['route'=> 'client.companies.settings.store']) !!}
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>variable</th>
                            <th>content</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($settings as $setting)
                            <tr>
                                <td>{{ $setting->id ?? null}}</td>
                                <td>{{ $setting->variable}}</td>
                                <td>{!! Form::text($setting->variable, $setting->content, ['class' => 'form-control']) !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! Form::submit('Submit', ['class' => '']) !!}
                    {!! Form::close() !!}
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
@stop