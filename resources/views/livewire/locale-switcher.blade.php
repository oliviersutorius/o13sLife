<div
    x-data
    @locale-changed.window="$wire.currentLocale = $event.detail.locale"
    class="flex items-center gap-1"
    role="navigation"
    aria-label="Sélecteur de langue"
>
    @foreach($locales as $code => $info)
        <button
            type="button"
            wire:click="switchLocale('{{ $code }}')"
            x-on:click="document.cookie = 'locale={{ $code }};path=/;max-age=31536000'; window.location.reload()"
            class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-sm transition-colors
                {{ $currentLocale === $code
                    ? 'bg-white/20 font-semibold text-white ring-1 ring-white/40'
                    : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
            title="{{ $info['label'] }}"
            aria-label="{{ $info['label'] }}"
            @if($currentLocale === $code) aria-current="true" @endif
        >
            <img src="{{ asset('images/flags/' . $info['flag'] . '.svg') }}" alt="" class="h-3 w-4 rounded-sm object-cover" />
        </button>
    @endforeach
</div>
