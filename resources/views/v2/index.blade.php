<x-v2.layouts.client>
    <div
        id="content"
        hx-get="{{route('v2.companies.index')}}"
        hx-trigger="load"
        hx-swap="innerHTML"
    >Dati tiek ielādēti....</div>
</x-v2.layouts.client>
