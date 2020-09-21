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
     <?/*   {!! Form::hidden('comnpany_id', isset($company) ? $company['id'] : null ) !!} */ ?>
    </div>
</div>

