<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('admin.login_title') }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 antialiased">

    <div class="flex min-h-screen items-center justify-center px-4">
        <div class="w-full max-w-sm">

            <h1 class="mb-8 text-center text-2xl font-semibold text-gray-900">
                {{ __('admin.login_title') }}
            </h1>

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-5" novalidate>
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('admin.email') }}
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                        aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-500 focus:outline-none focus:ring-1 focus:ring-gray-500 {{ $errors->has('email') ? 'border-red-500' : '' }}"
                    >
                    @error('email')
                    <p id="email-error" class="mt-1 text-xs text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ __('admin.password') }}
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-500 focus:outline-none focus:ring-1 focus:ring-gray-500"
                    >
                </div>

                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="h-4 w-4 rounded border-gray-300 text-gray-900"
                        aria-label="{{ __('admin.remember_me') }}"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-600">
                        {{ __('admin.remember_me') }}
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-md bg-gray-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 transition-colors"
                >
                    {{ __('admin.login_button') }}
                </button>
            </form>

        </div>
    </div>

</body>
</html>
