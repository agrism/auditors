@props(['column' => null, 'title' => null, 'sortColumn' => null, 'sortDirection' => null])

<div>
    <a href=""
       wire:click.prevent="sortBy('{{$column}}')"
    >
        {{$title}}
        <span
                class="fa @if($sortColumn == $column) @if($sortDirection === 'asc') fa-sort-up @else  fa-sort-down @endif @else fa-sort text-secondary  @endif"
        ></span>
    </a>
</div>