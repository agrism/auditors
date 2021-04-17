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
        {!! Form::text('title', isset($structuralunit) ? $structuralunit['title'] : null , ['class'=>'form-control', 'placeholder'=>'Input title'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('permissions', 'Permissions', ['class'=>'col-sm-2 control-label']) !!}
    @foreach($company->users as $user)
        <div class="col-sm-10">
            <input type="checkbox" value="{{$user->id}}" name="users[]" @if(in_array($user->id, !empty($structuralunit) ? $structuralunit->users->pluck('id')->all() : [])) checked @endif >
            {{$user->name}} {{$user->email}}
        </div>
    @endforeach
</div>

