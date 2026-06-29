@extends('admin.layouts.app')

@section('content')
<div class="rounded-lg bg-white p-6 shadow-sm">
    <h2 class="text-lg font-semibold text-gray-900">{{ __('admin.dashboard_title') }}</h2>
    <p class="mt-2 text-sm text-gray-600">{{ __('admin.dashboard_welcome') }}</p>
</div>
@endsection
