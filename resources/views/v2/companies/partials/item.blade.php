@if(isset($company))
<div hx-get="{{route('v2.companies.activate', $company->id)}}" hx-target="#selected-company" class="hover:bg-gray-50 cursor-pointer">
    <div class="grid md:grid-cols-2 gap-2 px-4 py-1.5">
        <div class="text-xs text-gray-900">{{$company->title}}</div>
        <div class="text-xs text-blue-600">{{$company->address}}</div>
    </div>
</div>
@endif
