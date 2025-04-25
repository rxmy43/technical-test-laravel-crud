<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'User Management')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- or your asset pipeline --}}
</head>

<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-4">
            <h1 class="text-2xl font-semibold">@yield('header', 'User Management')</h1>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 container mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Sticky Footer --}}
    <footer class="bg-white border-t py-4 text-center text-sm text-gray-500">
        Crafted with ❤️ using <span class="font-medium text-gray-700">Laravel</span> and
        <span class="font-medium text-gray-700">Tailwind CSS</span> — by
        <a href="https://github.com/rxmy43" target="_blank"
            class="font-semibold text-purple-700 hover:underline transition duration-200">
            Ramy Abyyu
        </a>.
    </footer>

    @stack('scripts')
</body>

</html>