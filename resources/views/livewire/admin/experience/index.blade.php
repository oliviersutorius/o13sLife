<div>
    @if($successMessage)
    <div class="mb-6 rounded-md bg-green-50 p-4 text-sm text-green-700" role="alert" aria-live="polite">
        {{ $successMessage }}
    </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('experience.titre_page') }}</h2>
        @if(!$showForm)
        <button
            type="button"
            wire:click="creer"
            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
        >
            {{ __('experience.ajouter') }}
        </button>
        @endif
    </div>

    @if($showForm)
    <div class="mb-8 rounded-lg bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-base font-medium text-gray-900">
            {{ $editingId ? __('experience.modifier') : __('experience.ajouter') }}
        </h3>

        <form wire:submit="sauvegarder" novalidate>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                {{-- Titre du poste --}}
                <div class="sm:col-span-2">
                    <label for="titre_poste" class="block text-sm font-medium text-gray-700">
                        {{ __('experience.titre_poste') }} <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="text"
                        id="titre_poste"
                        wire:model="titre_poste"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required
                        aria-required="true"
                        maxlength="255"
                    >
                    @error('titre_poste') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Entreprise --}}
                <div class="sm:col-span-2">
                    <label for="entreprise" class="block text-sm font-medium text-gray-700">
                        {{ __('experience.entreprise') }} <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="text"
                        id="entreprise"
                        wire:model="entreprise"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required
                        aria-required="true"
                        maxlength="255"
                    >
                    @error('entreprise') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Date début --}}
                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700">
                        {{ __('experience.date_debut') }} <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="date"
                        id="date_debut"
                        wire:model="date_debut"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required
                        aria-required="true"
                    >
                    @error('date_debut') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Date fin --}}
                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700">
                        {{ __('experience.date_fin') }}
                    </label>
                    <input
                        type="date"
                        id="date_fin"
                        wire:model="date_fin"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        aria-describedby="date_fin-hint"
                    >
                    <p id="date_fin-hint" class="mt-1 text-xs text-gray-500">{{ __('experience.date_fin_hint') }}</p>
                    @error('date_fin') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        {{ __('experience.description') }} <span class="text-red-500" aria-hidden="true">*</span>
                    </label>
                    <textarea
                        id="description"
                        wire:model="description"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required
                        aria-required="true"
                    ></textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
                </div>

                {{-- Technologies --}}
                <div class="sm:col-span-2">
                    <label for="technologies" class="block text-sm font-medium text-gray-700">
                        {{ __('experience.technologies') }}
                    </label>
                    <input
                        type="text"
                        id="technologies"
                        wire:model="technologies"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        aria-describedby="technologies-hint"
                        placeholder="PHP, Laravel, Vue.js"
                    >
                    <p id="technologies-hint" class="mt-1 text-xs text-gray-500">{{ __('experience.technologies_hint') }}</p>
                    @error('technologies') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button
                    type="button"
                    wire:click="annuler"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                >
                    {{ __('experience.annuler') }}
                </button>
                <button
                    type="submit"
                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                >
                    {{ __('experience.sauvegarder') }}
                </button>
            </div>
        </form>
    </div>
    @endif

    @if($items->isEmpty())
    <div class="rounded-lg bg-white p-8 text-center shadow-sm">
        <p class="text-sm text-gray-500">{{ __('experience.aucune_entree') }}</p>
    </div>
    @else
    <div class="overflow-hidden rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('experience.titre_poste') }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('experience.entreprise') }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('experience.date_debut') }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"> {{ __('common.statut') }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"> {{ __('common.traductions') }}</th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($items as $item)
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $item->titre_poste }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $item->entreprise }}</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                        {{ $item->date_debut->format('m/Y') }}
                        —
                        {{ $item->date_fin ? $item->date_fin->format('m/Y') : __('experience.poste_actuel') }}
                    </td>
                    <td class="whitespace-nowrap px-6 py-4">
                        @if($item->is_published)
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                {{ __('experience.statut_publie') }}
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                {{ __('experience.statut_brouillon') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @livewire('admin.translation-badges', [
                            'modelClass' => \App\Models\Experience::class,
                            'modelId'    => $item->id,
                            'fields'     => ['titre_poste', 'description'],
                        ], 'exp-badges-'.$item->id)
                    </td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <button
                                type="button"
                                wire:click="togglePublication({{ $item->id }})"
                                class="{{ $item->is_published ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }} focus:outline-none focus:underline"
                                aria-label="{{ $item->is_published ? __('experience.depublier') : __('experience.publier') }}"
                            >
                                {{ $item->is_published ? __('experience.depublier') : __('experience.publier') }}
                            </button>
                            <button
                                type="button"
                                wire:click="editer({{ $item->id }})"
                                class="text-blue-600 hover:text-blue-800 focus:outline-none focus:underline"
                            >
                                {{ __('experience.modifier') }}
                            </button>
                            <button
                                type="button"
                                wire:click="supprimer({{ $item->id }})"
                                wire:confirm="{{ __('experience.confirmation_suppression') }}"
                                class="text-red-600 hover:text-red-800 focus:outline-none focus:underline"
                                aria-label="{{ __('experience.supprimer') }} {{ $item->titre_poste }}"
                            >
                                {{ __('experience.supprimer') }}
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
