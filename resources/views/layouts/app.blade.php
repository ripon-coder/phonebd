<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PhoneBD')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">PhoneBD</a>
                <div class="flex space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600">Home</a>
                    <a href="{{ route('blog.index') }}" class="text-gray-600 hover:text-blue-600">Blog</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-white mt-8 py-4">
        <div class="container mx-auto px-4 text-center text-gray-600">
            &copy; {{ date('Y') }} PhoneBD. All rights reserved.
        </div>
    </footer>
</body>
</html>
