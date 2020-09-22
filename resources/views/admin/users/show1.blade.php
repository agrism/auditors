@extends('admin.layout.admin')

@section('content')

    <h1>User: {{ $user->name }}</h1>

    <div id="message"></div>
    <ul>

        <li>{{$user->email}} </li>

        <li>Partners:
            <ul class="partner-list">
                @foreach($user['partners'] as $partner)
                    <li class="partner-list-item">{{ $partner->name }}
                        - {!! link_to_route( 'admin.users.assignrole', 'set/edit Roles', ['user_id'=>$user, 'partner_id'=>$partner->id ]) !!}</li>
                @endforeach
            </ul>
        </li>
    </ul>
    <div class="form-group">


        {!! Form::label('partner_id', 'Assign to New Partner', ['class'=>'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::hidden('user_id', $user->id, ['id'=>'user_id']) !!}
            {!! Form::select('partner_id', $partners, null,['class'=>'form-control', 'placeholder'=>'']) !!}
        </div>

        <button id="assignToPartner">Assign partner</button>


    </div>
@stop


@section('js')
    <script type="text/javascript">
        $('document').ready(function () {
            $('#assignToPartner').on('click', function () {
                userId = $('#user_id').val();
                partnerId = $('#partner_id').val();
                token = "{{ csrf_token() }}";

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin.user.assignToPartner') }}",
                    data: {user_id: userId, partner_id: partnerId, _token: token},
                    dataType: 'json',
                    context: document.body
                }).done(function (data) {

                    if (data.response != 'error') {
                        $('.partner-list-item').remove();
                        $('#message').children().remove();
                        $.each(data.user, function (key, value) {
                            $('.partner-list').last().append('<li class="partner-list-item">' + value.name + ' - {!! link_to_route( 'admin.users.assignrole', 'set/edit Roles', ['id'=>'+$value.id+']) !!}</li>');
                            console.log(value.id);
                            console.log(value.name);
                        });
                    } else {
                        $('#message').append('<p style="color:red;">Error assigning to partner, ' + data.message + '</p>');
                    }
                }).fail(function () {

                });

            });
        })
    </script>
@stop




