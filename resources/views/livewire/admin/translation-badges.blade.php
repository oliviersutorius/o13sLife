<div class="flex items-center gap-2">
    @if($successMessage)
        <span class="text-xs text-green-600">{{ $successMessage }}</span>
    @endif

    <div class="flex items-center gap-1" aria-label="Langues disponibles">
        @foreach(\App\Livewire\Admin\TranslationBadges::LOCALE_FLAGS as $locale => $flag)
            <span
                class="inline-flex items-center rounded px-1.5 py-0.5 text-xs font-medium
                    {{ $translationStatus[$locale]
                        ? 'bg-green-100 text-green-800'
                        : 'bg-gray-100 text-gray-400' }}"
                title="{{ $translationStatus[$locale] ? 'Traduit en ' . strtoupper($locale) : 'Non traduit en ' . strtoupper($locale) }}"
                aria-label="{{ $flag }} {{ strtoupper($locale) }} : {{ $translationStatus[$locale] ? 'disponible' : 'manquant' }}"
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
        aria-label="Traduire automatiquement via l'agent IA"
    >
        <span wire:loading.remove wire:target="traduire">✨ Traduire</span>
        <span wire:loading wire:target="traduire">Traduction…</span>
    </button>
</div>
