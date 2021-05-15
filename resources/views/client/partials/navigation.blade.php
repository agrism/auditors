@if( \App::bound('Company') )
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand"
               href="{{ url(route('client.companies.index'))}}">{{  substr(\App\Services\SelectedCompanyService::getCompany()->title ?? 'not selected', 0,20) }}</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ \Request::route()->getName() == 'client.partners.index' ? 'active' : null }} "
                           aria-current="page" href="{{ url(route('client.partners.index')) }}">{{_('Partners')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ \Request::route()->getName() == 'client.invoices.index' ? 'active' : null }}"
                           href="{{ url(route('client.invoices.index')) }}">{{_('Invoices')}}</a>
                    </li>
                    @if(config('app.debug-available'))
                        <li class="nav-item">
                            <a class="nav-link {{ \Request::route()->getName() == 'client.personal-incomes.index' ? 'active' : null }}"
                               href="{{ url(route('client.personal-incomes.index')) }}">{{_('Personal incomes')}}</a>
                        </li>
                    @endif

                    <?php
                    $companyId = \App\Services\SelectedCompanyService::getCompanyId();
                    ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle @if(in_array(\Request::route()->getName() , ['client.companies.edit', 'client.companies.bank.index', 'client.companies.settings.index'])) active @endif"
                           href="#"
                           id="navbarDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            {{_('Self data')}} <span class="caret"></span>
                        </a>

                        @if($companyId)
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item"
                                   href="{{ url(route('client.companies.edit', $companyId)) }}">{{_('Requisites')}}</a>
                                <a class="dropdown-item"
                                   href="{{ url(route('client.companies.bank.index')) }}">{{_('Other payment receivers')}}</a>
                                <a class="dropdown-item"
                                   href="{{ url(route('client.companies.settings.index')) }}">{{_('Settings')}}</a>
                            </ul>
                        @endif
                    </li>
                </ul>

                {{--            </div>--}}


                {{--            <div>--}}

                <ul class="nav nav-pills">

                    @if(\Auth::check() && \Auth::user()->isAdmin())
                        <li class="{{ \Request::route()->getName() == 'admin.home' ? 'active' : null }} nav-link"><a
                                    href="{{ url(route('admin.home')) }}">Admin</a></li>
                    @endif
                    <li class="nav-item dropdown">
                        <?php
                        $user = explode(' ', \Illuminate\Support\Facades\Auth::user()->name ?? '')[0] ?? 'Account';
                        ?>
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">{{$user}} </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('client.user.edit') }}">{{ $user }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                        </ul>
                    </li>
                </ul>

            </div>

        </div>
    </nav>
@endif