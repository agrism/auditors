{!! Form::open(['action' => 'TpostController@index', 'method' => 'get']) !!}


<hr>
<ul>
    @foreach($category as $cat)
		<?php $selectedCategories = isset($selectedCategories) ? $selectedCategories : []?>
        <li>{{$cat->name}} {!! Form::checkbox('cat[]', $cat->id, ( in_array($cat['id'], $selectedCategories) ) ? 1 : 0 ) !!}</li>
    @endforeach
</ul>

{!! Form::submit() !!}
{!! Form::close() !!}


<hr>

<ol>
    @foreach($category as $cat)
        @foreach($cat['tpost'] as $post)
            <li>{{ $post['title'] }} </li>
        @endforeach
    @endforeach
</ol>
