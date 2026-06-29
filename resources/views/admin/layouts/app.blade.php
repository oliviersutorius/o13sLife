<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('admin.title') }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 antialiased">

    <nav class="bg-white shadow-sm" role="navigation" aria-label="{{ __('admin.main_nav') }}">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-900">
                    {{ config('app.name') }} — {{ __('admin.backoffice') }}
                </a>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">{{ Auth::user()->email }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-md bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors"
                            aria-label="{{ __('admin.logout') }}"
                        >
                            {{ __('admin.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8" id="main-content">
        @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4 text-sm text-green-700" role="alert">
            {{ session('success') }}
        </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
