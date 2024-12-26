<x-v2.layouts.guest>
    <div class="max-w-md w-full mx-4">
        <!-- Login Container -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Welcome back</h2>
                <p class="text-gray-600 mt-2">Please sign in to your account</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{route('v2.auth')}}" class="space-y-6">
                @csrf
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                        placeholder="Enter your email"
                    >
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                        placeholder="Enter your password"
                    >
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <div></div>
{{--                    <a href="/forgot-password" class="text-sm text-blue-600 hover:text-blue-800">--}}
{{--                        Forgot password?--}}
{{--                    </a>--}}
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                >
                    Sign In
                </button>
            </form>

            <!-- Sign Up Link -->
{{--            <div class="text-center mt-6">--}}
{{--                <p class="text-sm text-gray-600">--}}
{{--                    Don't have an account?--}}
{{--                    <a href="/register" class="text-blue-600 hover:text-blue-800 font-medium">--}}
{{--                        Sign up--}}
{{--                    </a>--}}
{{--                </p>--}}
{{--            </div>--}}
        </div>
    </div>
</x-v2.layouts.guest>
