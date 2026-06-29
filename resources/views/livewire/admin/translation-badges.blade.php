<div class="flex items-center gap-2">
    @if($successMessage)
        <span class="text-xs text-green-600">{{ $successMessage }}</span>
    @endif

    <div class="flex items-center gap-1" aria-label="{{ __('common.langues_disponibles') }}">
        @foreach(\App\Livewire\Admin\TranslationBadges::LOCALE_FLAGS as $locale => $flag)
            <span
                class="inline-flex items-center rounded px-1.5 py-0.5 text-xs font-medium
                    {{ $translationStatus[$locale]
                        ? 'bg-green-100 text-green-800'
                        : 'bg-gray-100 text-gray-400' }}"
                title="{{ $translationStatus[$locale] ? __('common.traduit_en', ['locale' => strtoupper($locale)]) : __('common.non_traduit_en', ['locale' => strtoupper($locale)]) }}"
                aria-label="{{ $flag }} {{ strtoupper($locale) }} : {{ $translationStatus[$locale] ? __('common.disponible') : __('common.manquant') }}"
            >
                {{ $flag }}
            </span>
        @endforeach
    </div>

    <button
        type="button"
        wire:click="traduire"
        wire:loading.attr="disabled"
        class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 disabled:opacity-50 transition-colors"
        aria-label="{{ __('common.traduire_aria') }}"
    >
        <span wire:loading.remove wire:target="traduire">✨ {{ __('common.traduire') }}</span>
        <span wire:loading wire:target="traduire">{{ __('common.traduction_en_cours') }}</span>
    </button>
</div>
