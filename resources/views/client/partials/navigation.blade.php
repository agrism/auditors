@if( \App::bound('Company') )
    <nav class="navbar navbar-inverse ">
        <div class="container">
            <div class="navbar-header">
                {{--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">--}}
                {{--<span class="sr-only">Toggle navigation</span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--</button>--}}
                <a class="navbar-brand" href="#">
                    <div class="fa fa-adn"></div>
                </a>
                <a class="navbar-brand"
                   href="{{ url(route('client.companies.index'))}}">Client: {{  substr(\App\Services\SelectedCompanyService::getCompany()->title ?? 'not selected', 0,20) }}</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="{{ \Request::route()->getName() == 'client.partners.index' ? 'active' : null }}"><a
                                href="{{ url(route('client.partners.index')) }}">{{_('Partners')}}</a></li>

                    <li class="{{ \Request::route()->getName() == 'client.invoices.index' ? 'active' : null }}"><a
                                href="{{ url(route('client.invoices.index')) }}">{{_('Invoices')}}</a></li>

                    <li class="{{ \Request::route()->getName() == 'client.personal-incomes.index' ? 'active' : null }}">
                        <a href="{{ url(route('client.personal-incomes.index')) }}">{{_('Personal incomes')}}</a></li>


					<?php
					$companyId = \App\Services\SelectedCompanyService::getCompanyId();
					?>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="true">{{_('Self data')}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">

                            @if($companyId)
                                <li class="{{ \Request::route()->getName() == 'client.companies.edit' ? 'active' : null }}">
                                    <a href="{{ url(route('client.companies.edit', $companyId)) }}">{{_('Requisites')}}</a>
                                </li>


                                <li class="{{ \Request::route()->getName() == 'client.companies.bank.index' ? 'active' : null }}">
                                    <a href="{{ url(route('client.companies.bank.index')) }}">{{_('Other payment receivers')}}</a>
                                </li>

                            @endif

                            {{-- <li><a href="#">Another action</a></li> --}}
                            {{-- <li><a href="#">Something else here</a></li> --}}
                            <li role="separator" class="divider"></li>
                            {{-- <li class="dropdown-header">Nav header</li> --}}
                            {{-- <li><a href="#">Separated link</a></li> --}}
                            {{-- <li><a href="#">One more separated link</a></li> --}}
                        </ul>
                    </li>


                </ul>

                <ul class="nav navbar-nav navbar-right">
                    @if(\Auth::check() && \Auth::user()->isAdmin())
                        <li class="{{ \Request::route()->getName() == 'admin.home' ? 'active' : null }}"><a
                                    href="{{ url(route('admin.home')) }}">Admin</a></li>
                    @endif
                    <li>
                        <a style="color:white;background-color: dimgrey">[closed:{{ \App\Services\SelectedCompanyService::getCompany()->closed_data_date ?? null  }}
                            ]</a></li>
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    </div>
@endif
