<div>
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">{{ __('profil.titre_page') }}</h2>
        @if($is_published)
            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                {{ __('profil.statut_publie') }}
            </span>
        @else
            <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800">
                {{ __('profil.statut_brouillon') }}
            </span>
        @endif
    </div>

    <form wire:submit="sauvegarder" enctype="multipart/form-data" novalidate>
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm">

            {{-- Photo --}}
            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700">
                    {{ __('profil.photo') }}
                </label>
                @if($photo_actuelle)
                    <div class="mt-2 mb-3">
                        <img
                            src="{{ Storage::url($photo_actuelle) }}"
                            alt="{{ __('profil.photo_actuelle_alt') }}"
                            class="h-24 w-24 rounded-full object-cover"
                        >
                    </div>
                @endif
                @if($photo)
                    <div class="mt-2 mb-3">
                        <img
                            src="{{ $photo->temporaryUrl() }}"
                            alt="{{ __('profil.photo_apercu_alt') }}"
                            class="h-24 w-24 rounded-full object-cover ring-2 ring-blue-300"
                        >
                    </div>
                @endif
                <input
                    type="file"
                    id="photo"
                    wire:model="photo"
                    accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100"
                    aria-describedby="photo-hint"
                >
                <p id="photo-hint" class="mt-1 text-xs text-gray-500">{{ __('profil.photo_hint') }}</p>
                @error('photo') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
                <div wire:loading wire:target="photo" class="mt-1 text-sm text-blue-600">{{ __('profil.chargement') }}</div>
            </div>

            {{-- Titre professionnel --}}
            <div>
                <label for="titre" class="block text-sm font-medium text-gray-700">
                    {{ __('profil.titre_pro') }} <span class="text-red-500" aria-hidden="true">*</span>
                </label>
                <input
                    type="text"
                    id="titre"
                    wire:model="titre"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    required
                    aria-required="true"
                    maxlength="255"
                >
                @error('titre') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    {{ __('profil.email') }} <span class="text-red-500" aria-hidden="true">*</span>
                </label>
                <input
                    type="email"
                    id="email"
                    wire:model="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    required
                    aria-required="true"
                    maxlength="255"
                >
                @error('email') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
            </div>

            {{-- Téléphone --}}
            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700">
                    {{ __('profil.telephone') }}
                </label>
                <input
                    type="tel"
                    id="telephone"
                    wire:model="telephone"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    maxlength="50"
                >
                @error('telephone') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
            </div>

            {{-- Localisation --}}
            <div>
                <label for="localisation" class="block text-sm font-medium text-gray-700">
                    {{ __('profil.localisation') }}
                </label>
                <input
                    type="text"
                    id="localisation"
                    wire:model="localisation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    maxlength="255"
                >
                @error('localisation') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
            </div>

            {{-- LinkedIn --}}
            <div>
                <label for="lien_linkedin" class="block text-sm font-medium text-gray-700">
                    {{ __('profil.lien_linkedin') }}
                </label>
                <input
                    type="url"
                    id="lien_linkedin"
                    wire:model="lien_linkedin"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    maxlength="255"
                    placeholder="https://linkedin.com/in/..."
                >
                @error('lien_linkedin') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
            </div>

            {{-- GitHub --}}
            <div>
                <label for="lien_github" class="block text-sm font-medium text-gray-700">
                    {{ __('profil.lien_github') }}
                </label>
                <input
                    type="url"
                    id="lien_github"
                    wire:model="lien_github"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    maxlength="255"
                    placeholder="https://github.com/..."
                >
                @error('lien_github') <p class="mt-1 text-sm text-red-600" role="alert">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-4 flex items-center justify-end gap-3">
            <button
                type="submit"
                class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                aria-label="{{ __('profil.sauvegarder_aria') }}"
            >
                {{ __('profil.sauvegarder') }}
            </button>
            <button
                type="button"
                wire:click="publier"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                aria-label="{{ __('profil.publier_aria') }}"
            >
                {{ __('profil.publier') }}
            </button>
        </div>
    </form>
</div>
