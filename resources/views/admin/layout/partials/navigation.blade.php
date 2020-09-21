<nav class="navbar navbar-inverse navbar-light bg faded">
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
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="{{ \Request::route()->getName() == 'admin.home' ? 'active' : null }}"><a href="{{ url(route('admin.home')) }}">{{_('Companies')}}</a></li>
                <li class="{{ \Request::route()->getName() == 'admin.users.index' ? 'active' : null }}"><a href="{{ url(route('admin.users.index')) }}">{{_('Users')}}</a></li>
                <li class="{{ \Request::route()->getName() == 'admin.invoices.index' ? 'active' : null }}"><a href="{{ url(route('admin.invoices.index')) }}">{{_('Invoices')}}</a></li>

                <li class="{{ \Request::route()->getName() == 'admin.export' ? 'active' : null }}"><a href="{{ url(route('admin.export')) }}">{{_('Export data')}}</a></li>
                <li class="{{ \Request::route()->getName() == 'admin.npi' ? 'active' : null }}"><a href="{{ url(route('admin.npi')) }}">{{_('NPI')}}</a></li>

                <li class="dropdown
                    {{ \Request::route()->getName() == 'admin.roles.index' ? 'active' : null }}
                    {{ \Request::route()->getName() == 'admin.permissions.index' ? 'active' : null }}"
                >
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{_('Roles')}} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ \Request::route()->getName() == 'admin.roles.index' ? 'active' : null }}"><a href="{{ url(route('admin.roles.index')) }}">{{_('Roles')}}</a></li>
                        <li class="{{ \Request::route()->getName() == 'admin.permissions.index' ? 'active' : null }}"><a href="{{ url(route('admin.permissions.index')) }}">{{_('Permissions')}}</a></li>
                        {{--<li class="{{ \Request::route()->getName() == 'admin.permission.roles.index' ? 'active' : null }}"><a href="{{ url(route('admin.permissions.roles.index')) }}">Permissions assign To Roles</a></li>--}}

                    </ul>


            </ul>


            <ul class="nav navbar-nav navbar-right">
                <li class="{{ \Request::route()->getName() == 'client.index' ? 'active' : null }}"><a href="{{ url(route('client.index')) }}">Clientside</a></li>

                <li><a href="{{ route('logout') }}">Logout</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
</div>