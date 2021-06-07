<div>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-truncate" style="max-width: 300px;overflow: hidden">{{\App\Services\AuthUser::instance()->selectedCompany()->title ?? '-'}}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    @foreach($this->nav() as $sysName => $item)

                        @if(!$item['available'])
                            @continue
                        @endif
                        <li class="nav-item">
                            <span class="nav-link @if($item['active']) active @endif"
                                  role="button"
                                  wire:click="activateComponent('{{$sysName}}')"
                            >
                                {{$item['title']}}
                            </span>
                        </li>

                    @endforeach


                    <?php
                    $companyId = \App\Services\AuthUser::instance()->selectedCompanyId();
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
                <ul class="nav nav-pills">

                    @if(\Auth::check() && \Auth::user()->isAdmin())
                        <li class="{{ \Request::route()->getName() == 'admin.home' ? 'active' : null }} nav-link"><a
                                    href="{{ url(route('admin.home')) }}">Admin</a></li>
                    @endif
                    <li class="nav-item dropdown">
                        <?php
                        $user = explode(' ', \Illuminate\Support\Facades\Auth::user()->name ?? '')[0] ?? 'Account';
                        ?>
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                           aria-expanded="false">{{$user}} </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('client.user.edit') }}">{{ $user }}</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div style="margin-top: 55px;">
        <div wire:loading style="position: absolute">
            <x-loading loading="true"></x-loading>
        </div>


        {{--        {{json_encode($this->nav)}}--}}
        <div class="container">
            @if(!$companyId)
                <livewire:company-list/>
            @elseif($this->activeComponent() === 'companies')
                <livewire:company-list/>
            @elseif($this->activeComponent() === 'invoices')
                <livewire:invoice-list/>
            @elseif($this->activeComponent() === 'partners')
                <livewire:partner-list/>
            @endif
        </div>

    </div>


</div>
