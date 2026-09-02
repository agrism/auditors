<nav class="navbar navbar-inverse navbar-light bg faded">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <div class="fa fa-adn"></div>
            </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="{{ request()->routeIs('admin.home') ? 'active' : '' }}"><a
                            href="{{ url(route('admin.home')) }}">{{ __('Companies') }}</a></li>
                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><a
                            href="{{ url(route('admin.users.index')) }}">{{ __('Users') }}</a></li>
                <li class="{{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}"><a
                            href="{{ url(route('admin.invoices.index')) }}">{{ __('Invoices') }}</a></li>

                <li class="dropdown">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="true">Export <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('admin.export') ? 'active' : '' }}"><a
                                    href="{{ url(route('admin.export')) }}">{{ __('Export data') }}</a></li>
                        <li class="{{ request()->routeIs('admin.npi') ? 'active' : '' }}"><a
                                    href="{{ url(route('admin.npi')) }}">{{ __('NPI') }}</a></li>
                        <li class="{{ request()->routeIs('admin.working-hours.*') ? 'active' : '' }}"><a
                                    href="{{ url(route('admin.working-hours.index')) }}">{{ __('Working hours') }}</a></li>
                        <li class="{{ request()->routeIs('admin.vacations.*') ? 'active' : '' }}"><a
                                    href="{{ url(route('admin.vacations.index')) }}">{{ __('Vacations') }}</a></li>
                        <li class="{{ request()->routeIs('admin.vat.*') ? 'active' : '' }}"><a
                                    href="{{ url(route('admin.vat.index')) }}">{{ __('Vat return') }}</a></li>
                    </ul>
                </li>

                <li class="dropdown {{ (request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*')) ? 'active' : '' }}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="true">{{ __('Roles') }} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"><a
                                    href="{{ url(route('admin.roles.index')) }}">{{ __('Roles') }}</a></li>
                        <li class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"><a
                                    href="{{ url(route('admin.permissions.index')) }}">{{ __('Permissions') }}</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="{{ request()->routeIs('client.*') ? 'active' : '' }}"><a
                            href="{{ url(route('client.index')) }}">Clientside</a></li>

                <li><a href="{{ route('logout') }}">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>