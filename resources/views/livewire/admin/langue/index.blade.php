<div>
    @if($successMessage)
    <div class="mb-6 rounded-md bg-green-50 p-4 text-sm text-green-700" role="alert" aria-live="polite">
        {{ $successMessage }}
    </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('langue.titre_page') }}</h2>
        @if(!$showForm)
        <button
            type="button"
            wire:click="creer"
            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
        >
            {{ __('langue.ajouter') }}
        </button>
        @endif
    </div>

    @if($showForm)
    <div class="mb-8 rounded-lg bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-base font-medium text-gray-900">
            {{ $editingId ? __('langue.modifier') : __('langue.ajouter') }}
        </h3>

        <form wire:submit="sauvegarder" novalidate>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                {{-- Langue --}}
                <div>
                    <label for="langue" class="block text-sm font-medium text-gray-700">
                        {{ __('langue.langue') }} <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="text"
                        id="langue"
                        wire:model="langue"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required
                        aria-required="true"
                        maxlength="255"
                        placeholder="Français, Anglais…"
                    >
                    @error('langue') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Niveau --}}
                <div>
                    <label for="niveau" class="block text-sm font-medium text-gray-700">
                        {{ __('langue.niveau') }} <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="text"
                        id="niveau"
                        wire:model="niveau"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required
                        aria-required="true"
                        maxlength="255"
                        placeholder="Natif, Professionnel (C1), DALF C2…"
                    >
                    @error('niveau') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button
                    type="button"
                    wire:click="annuler"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                >
                    {{ __('langue.annuler') }}
                </button>
                <button
                    type="submit"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                >
                    {{ __('langue.sauvegarder') }}
                </button>
            </div>
        </form>
    </div>
    @endif

    @if($items->isEmpty())
    <div class="rounded-lg bg-white p-8 text-center shadow-sm">
        <p class="text-sm text-gray-500">{{ __('langue.aucune_entree') }}</p>
    </div>
    @else
    <div class="overflow-hidden rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('langue.langue') }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('langue.niveau') }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"> {{ __('common.statut') }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"> {{ __('common.traductions') }}</th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($items as $item)
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $item->langue }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $item->niveau }}</td>
                    <td class="whitespace-nowrap px-6 py-4">
                        @if($item->is_published)
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                {{ __('langue.statut_publie') }}
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                {{ __('langue.statut_brouillon') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @livewire('admin.translation-badges', [
                            'modelClass' => \App\Models\Langue::class,
                            'modelId'    => $item->id,
                            'fields'     => ['niveau'],
                        ], 'lang-badges-'.$item->id)
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <button
                                type="button"
                                wire:click="togglePublication({{ $item->id }})"
                                class="{{ $item->is_published ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }} focus:outline-none focus:underline"
                                aria-label="{{ $item->is_published ? __('langue.depublier') : __('langue.publier') }}"
                            >
                                {{ $item->is_published ? __('langue.depublier') : __('langue.publier') }}
                            </button>
                            <button
                                type="button"
                                wire:click="editer({{ $item->id }})"
                                class="text-blue-600 hover:text-blue-800 focus:outline-none focus:underline"
                            >
                                {{ __('langue.modifier') }}
                            </button>
                            <button
                                type="button"
                                wire:click="supprimer({{ $item->id }})"
                                wire:confirm="{{ __('langue.confirmation_suppression') }}"
                                class="text-red-600 hover:text-red-800 focus:outline-none focus:underline"
                                aria-label="{{ __('langue.supprimer') }} {{ $item->langue }}"
                            >
                                {{ __('langue.supprimer') }}
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
