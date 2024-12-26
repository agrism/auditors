<!-- Main Container -->


<x-v2.title>Mani uzņēmumi</x-v2.title>

<div class="max-w-7xl mx-auto bg-white rounded-lg shadow">
    <!-- List Header -->
    <div class="hidden md:grid grid-cols-2 gap-2 px-4 py-2 bg-gray-100 border-b border-gray-200 text-xs">
        <div class="font-medium text-gray-600">Nosaukums</div>
        <div class="font-medium text-gray-600">Adrese</div>
    </div>


    <div class="divide-y divide-gray-200">
        @foreach($companies ?? [] as $company)
            @include('v2.companies.partials.item', compact('company'))
        @endforeach
    </div>
</div>
