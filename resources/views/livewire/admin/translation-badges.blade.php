<div class="space-y-2">

    {{-- Ligne badges + actions --}}
    <div class="flex items-center gap-2">
        @if($successMessage)
            <span class="text-xs text-green-600" role="status">{{ $successMessage }}</span>
        @endif

        <div class="flex items-center gap-1" aria-label="{{ __('common.langues_disponibles') }}">
            @foreach(\App\Livewire\Admin\TranslationBadges::LOCALE_FLAGS as $locale => $flag)
                @php $status = $translationStatus[$locale] ?? 'missing'; @endphp
                <span
                    class="inline-flex items-center rounded px-1.5 py-0.5 text-xs font-medium
                        @if($status === 'validated') bg-green-100 text-green-800
                        @elseif($status === 'auto') bg-amber-100 text-amber-700
                        @else bg-gray-100 text-gray-400
                        @endif"
                    title="@if($status === 'validated'){{ __('common.statut_valide', ['locale' => strtoupper($locale)]) }}@elseif($status === 'auto'){{ __('common.statut_auto', ['locale' => strtoupper($locale)]) }}@else{{ __('common.non_traduit_en', ['locale' => strtoupper($locale)]) }}@endif"
                    aria-label="{{ $flag }} {{ strtoupper($locale) }} : @if($status === 'validated'){{ __('common.valide') }}@elseif($status === 'auto'){{ __('common.auto') }}@else{{ __('common.manquant') }}@endif"
                >
                    {{ $flag }}
                </span>
            @endforeach
        </div>

        <button
            type="button"
            wire:click="toggleEditor"
            class="inline-flex items-center gap-1 rounded-md border border-gray-200 bg-white px-2 py-1 text-xs font-medium text-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition-colors"
            aria-expanded="{{ $showEditor ? 'true' : 'false' }}"
            aria-label="{{ __('common.editer_traductions') }}"
        >
            ✏️ {{ __('common.editer') }}
        </button>

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

    {{-- Légende --}}
    <div class="flex items-center gap-3 text-xs text-gray-400">
        <span><span class="inline-block h-2 w-2 rounded-full bg-green-400"></span> {{ __('common.legende_valide') }}</span>
        <span><span class="inline-block h-2 w-2 rounded-full bg-amber-400"></span> {{ __('common.legende_auto') }}</span>
        <span><span class="inline-block h-2 w-2 rounded-full bg-gray-300"></span> {{ __('common.legende_manquant') }}</span>
    </div>

    {{-- Éditeur de traductions --}}
    @if($showEditor)
    <div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-4" role="region" aria-label="{{ __('common.editeur_traductions') }}">
        <h4 class="mb-3 text-sm font-medium text-gray-700">{{ __('common.editeur_traductions') }}</h4>

        {{-- Référence FR --}}
        <div class="mb-4 rounded-md bg-white p-3 ring-1 ring-gray-200">
            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">🇫🇷 {{ __('common.reference_fr') }}</p>
            @foreach($fields as $field)
                <p class="text-xs text-gray-600">
                    <span class="font-medium">{{ $this->labelFor($field) }} :</span>
                    {{ $this->frValue($field) ?: '—' }}
                </p>
            @endforeach
        </div>

        {{-- Champs par locale --}}
        <div class="space-y-4">
            @foreach(\App\Livewire\Admin\TranslationBadges::LOCALE_FLAGS as $locale => $flag)
                @if($locale === 'fr') @continue @endif
                @php $status = $translationStatus[$locale] ?? 'missing'; @endphp
                <div class="rounded-md bg-white p-3 ring-1 ring-gray-200">
                    <div class="mb-2 flex items-center gap-2">
                        <span class="text-sm">{{ $flag }}</span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ strtoupper($locale) }}</span>
                        @if($status === 'validated')
                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs text-green-700">{{ __('common.valide') }}</span>
                        @elseif($status === 'auto')
                            <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700">{{ __('common.auto') }}</span>
                        @else
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500">{{ __('common.manquant') }}</span>
                        @endif
                    </div>

                    @foreach($fields as $field)
                        <div class="mt-2">
                            <label
                                for="translation-{{ $locale }}-{{ $field }}"
                                class="block text-xs font-medium text-gray-600"
                            >
                                {{ $this->labelFor($field) }}
                            </label>
                            @if(strlen($this->frValue($field)) > 80)
                                <textarea
                                    id="translation-{{ $locale }}-{{ $field }}"
                                    wire:model="translations.{{ $locale }}.{{ $field }}"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    aria-label="{{ __('common.champ_en_locale', ['champ' => $this->labelFor($field), 'locale' => strtoupper($locale)]) }}"
                                ></textarea>
                            @else
                                <input
                                    type="text"
                                    id="translation-{{ $locale }}-{{ $field }}"
                                    wire:model="translations.{{ $locale }}.{{ $field }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    aria-label="{{ __('common.champ_en_locale', ['champ' => $this->labelFor($field), 'locale' => strtoupper($locale)]) }}"
                                >
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="mt-4 flex items-center justify-end gap-3">
            <button
                type="button"
                wire:click="toggleEditor"
                class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition-colors"
            >
                {{ __('common.annuler') }}
            </button>
            <button
                type="button"
                wire:click="sauvegarderTraductions"
                wire:loading.attr="disabled"
                class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 disabled:opacity-50 transition-colors"
            >
                <span wire:loading.remove wire:target="sauvegarderTraductions">{{ __('common.sauvegarder_traductions') }}</span>
                <span wire:loading wire:target="sauvegarderTraductions">{{ __('common.sauvegarde_en_cours') }}</span>
            </button>
        </div>
    </div>
    @endif
</div>
