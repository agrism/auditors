@props(['type' => 'text', 'name' => 'name', 'value'=> null, 'label' => 'Label', 'placeholder' => null] )

<div class="form-group">
    <label for="{{$name}}" class="col-form-label-sm">{{$label}}</label>
    <div class="input-group input-group-sm">
        <input type="{{$type}}" name="{{$name}}" value="{{$value}}" placeholder="{{$placeholder}}" class="form-control">
        {{$slot}}
    </div>
</div>