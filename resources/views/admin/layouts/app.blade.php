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

    <nav class="border-b border-gray-200 bg-white" aria-label="{{ __('admin.secondary_nav') }}">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-10 items-center gap-6 overflow-x-auto">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} pb-0.5"
                >
                    {{ __('admin.dashboard_title') }}
                </a>
                <a
                    href="{{ route('admin.profil.edit') }}"
                    class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('admin.profil.edit') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} pb-0.5"
                >
                    {{ __('profil.titre_page') }}
                </a>
                <a
                    href="{{ route('admin.experience.index') }}"
                    class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('admin.experience.index') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} pb-0.5"
                >
                    {{ __('experience.titre_page') }}
                </a>
                <a
                    href="{{ route('admin.formation.index') }}"
                    class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('admin.formation.index') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} pb-0.5"
                >
                    {{ __('formation.titre_page') }}
                </a>
                <a
                    href="{{ route('admin.competence.index') }}"
                    class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('admin.competence.index') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} pb-0.5"
                >
                    {{ __('competence.titre_page') }}
                </a>
                <a
                    href="{{ route('admin.langue.index') }}"
                    class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('admin.langue.index') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} pb-0.5"
                >
                    {{ __('langue.titre_page') }}
                </a>
                <a
                    href="{{ route('admin.centre-interet.index') }}"
                    class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('admin.centre-interet.index') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} pb-0.5"
                >
                    {{ __('centre_interet.titre_page') }}
                </a>
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
