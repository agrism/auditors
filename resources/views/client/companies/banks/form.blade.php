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
    {!! Form::label('payment_receiver', 'Payment Receiver', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('payment_receiver', isset($bank) ? $bank['payment_receiver'] : null , ['class'=>'form-control', 'placeholder'=>'Input payment receiver'] ) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('comment', 'Internal comment', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('comment', isset($bank) ? $bank['comment'] : null , ['class'=>'form-control', 'placeholder'=>'Internal comment'] ) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('bank', 'Bank', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('bank', isset($bank) ? $bank['bank'] : null , ['class'=>'form-control', 'placeholder'=>'Input bank'] ) !!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('swift', 'SWIFT', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('swift', isset($bank) ? $bank['swift'] : null , ['class'=>'form-control', 'placeholder'=>'Input SWIFT'] ) !!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('account_number', 'Account No', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('account_number', isset($bank) ? $bank['account_number'] : null , ['class'=>'form-control', 'placeholder'=>'Input Account No'] ) !!}
    </div>
</div>


