<nav class="bg-gray-800" id="nav">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between">
            <!-- Left side -->
            <div class="flex space-x-4">
                <!-- Logo -->
                <div class="flex items-center space-x-1 w-8 h-8">
                <div class="w-6 h-6 bg-white text-gray-800 flex items-center justify-center font-bold rounded-full text-sm py-1 px-3">
                    A
                </div>
                </div>
                <!-- Primary Nav -->
                <div class="hidden sm:flex items-center space-x-1">

                    @foreach(\App\Services\V2\MenuHelper::instance()->menu() as $route => $name)
                    <a hx-get="{{$route}}" hx-target="#content" class="py-1 px-3 text-white hover:text-gray-300 cursor-pointer">{{$name}}</a>
                    @endforeach
                </div>
            </div>
            <div class="ml-auto">
                <div class="py-1 px-3 text-white" id="selected-company">{{\App\Services\V2\UserCompanyHelper::instance()->getSelectedCompany()?->title ?? '-'}}</div>
            </div>

            <!-- Secondary Nav -->
            {{--            <div class="hidden md:flex items-center space-x-1">--}}
            {{--                <a href="#" class="py-2 px-3 bg-gray-700 hover:bg-gray-600 text-white rounded">Login</a>--}}
            {{--                <a href="#" class="py-2 px-3 bg-blue-600 hover:bg-blue-500 text-white rounded">Signup</a>--}}
            {{--            </div>--}}

            <!-- Mobile button -->
            <div class="sm:hidden flex items-center">
                <button class="mobile-menu-button" onclick="toggleMenu()">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"
                        ></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu hidden sm:hidden">
        @foreach(\App\Services\V2\MenuHelper::instance()->menu() as $route => $name)
        <a hx-get="{{$route}}" hx-target="#content" class="block py-2 px-4 text-sm text-white hover:bg-gray-700 cursor-pointer" onclick="toggleMenu()">{{$name}}</a>
        @endforeach
        {{--        <div class="py-5">--}}
        {{--            <a href="#" class="block py-2 px-4 text-sm text-white bg-gray-700 hover:bg-gray-600">Login</a>--}}
        {{--            <a href="#" class="block py-2 px-4 text-sm text-white bg-blue-600 hover:bg-blue-500 mt-1">Signup</a>--}}
        {{--        </div>--}}
    </div>
</nav>

