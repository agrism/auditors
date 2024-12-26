<!-- Main Container -->


<x-v2.title>Rēķini</x-v2.title>

<div class="max-w-7xl mx-auto bg-white rounded-lg shadow">
    <!-- List Header -->
    <div class="hidden md:grid grid-cols-6 gap-2 px-4 py-2 bg-gray-100 border-b border-gray-200 text-xs">
        <div class="font-medium text-gray-600">Date</div>
        <div class="font-medium text-gray-600">Number</div>
        <div class="font-medium text-gray-600 col-span-2">Partner</div>
        <div class="font-medium text-gray-600">Currency</div>
        <div class="font-medium text-gray-600 text-right">Amount</div>
    </div>

    <!-- Invoice List -->
    <div class="divide-y divide-gray-200">
        <!-- Invoice Row -->
        <div class="hover:bg-gray-50">
            <div class="grid md:grid-cols-6 gap-2 px-4 py-1.5">
                <div class="text-xs text-gray-900">15/03/2024</div>
                <div class="text-xs text-blue-600">INV-2024-001</div>
                <div class="text-xs text-gray-900 col-span-2">Acme Corporation</div>
                <div class="text-xs text-gray-600">USD</div>
                <div class="text-xs text-gray-900 text-right">1,250.00</div>
            </div>
        </div>

        <!-- Invoice Row -->
        <div class="hover:bg-gray-50">
            <div class="grid md:grid-cols-6 gap-2 px-4 py-1.5">
                <div class="text-xs text-gray-900">14/03/2024</div>
                <div class="text-xs text-blue-600">INV-2024-002</div>
                <div class="text-xs text-gray-900 col-span-2">TechStart Inc</div>
                <div class="text-xs text-gray-600">EUR</div>
                <div class="text-xs text-gray-900 text-right">2,780.50</div>
            </div>
        </div>

        @foreach(range(1, 40) as $inv)
            <!-- Invoice Row -->
            <div class="hover:bg-gray-50">
                <div class="grid md:grid-cols-6 gap-2 px-4 py-1.5">
                    <div class="text-xs text-gray-900">13/03/2024</div>
                    <div class="text-xs text-blue-600">INV-2024-003</div>
                    <div class="text-xs text-gray-900 col-span-2">Global Solutions Ltd Global Solutions Ltd Global Solutions Ltd Global Solutions Ltd</div>
                    <div class="text-xs text-gray-600">USD</div>
                    <div class="text-xs text-gray-900 text-right">3,420.75</div>
                </div>
            </div>
        @endforeach



        <!-- More rows... -->
    </div>
</div>
