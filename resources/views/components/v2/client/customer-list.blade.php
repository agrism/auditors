<nav class="py-4 overflow-y-auto h-[calc(100vh-4rem)]">
    <div class="px-3 pb-2">
        <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tavi uzņēmumi</h2>
    </div>

    @foreach(\App\Services\V2\UserCompanyHelper::instance()->getUserCompanies() as $company)
        <a hx-get="{{route('v2.companies.activate', $company->id)}}"
           hx-target="#customer-nav-list"
           hx-swap="innerHTML"
           class="flex items-center px-3 py-2 text-sm text-gray-900 bg-gray-100 cursor-pointer"
        >
            <span class="border-l-4 pl-2 @if($company->id == \App\Services\V2\UserCompanyHelper::instance()->getSelectedCompany()?->id) border-blue-600 @endif">{{$company->title}}</span>
        </a>
    @endforeach
</nav>
