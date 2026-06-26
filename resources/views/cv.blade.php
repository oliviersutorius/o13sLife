<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $profil?->titre ?? __('cv.meta_description') }}">
    <title>{{ $profil?->titre ?? __('cv.title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">

    {{-- En-tête / Profil --}}
    @if($profil)
    <header class="bg-white shadow-sm" role="banner">
        <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-start">

                @if($profil->photo)
                <img
                    src="{{ asset('storage/' . $profil->photo) }}"
                    alt="{{ __('cv.profile_photo_alt') }}"
                    class="h-28 w-28 rounded-full object-cover shadow-md ring-2 ring-gray-100"
                >
                @endif

                <div class="text-center sm:text-left">
                    <h1 class="text-3xl font-semibold tracking-tight text-gray-900">
                        {{ $profil->titre }}
                    </h1>

                    <div class="mt-3 flex flex-wrap justify-center gap-4 text-sm text-gray-500 sm:justify-start" aria-label="{{ __('cv.contact_info') }}">
                        @if($profil->localisation)
                        <span class="flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $profil->localisation }}
                        </span>
                        @endif

                        <a href="mailto:{{ $profil->email }}" class="flex items-center gap-1 hover:text-gray-900 transition-colors" aria-label="{{ __('cv.send_email') }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $profil->email }}
                        </a>

                        @if($profil->telephone)
                        <a href="tel:{{ $profil->telephone }}" class="flex items-center gap-1 hover:text-gray-900 transition-colors" aria-label="{{ __('cv.call') }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $profil->telephone }}
                        </a>
                        @endif
                    </div>

                    <div class="mt-3 flex justify-center gap-3 sm:justify-start">
                        @if($profil->lien_linkedin)
                        <a href="{{ $profil->lien_linkedin }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-blue-600 transition-colors" aria-label="LinkedIn">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        @endif

                        @if($profil->lien_github)
                        <a href="{{ $profil->lien_github }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-gray-900 transition-colors" aria-label="GitHub">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
    @endif

    <main class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8" id="main-content">

        {{-- Expériences --}}
        @if($experiences->isNotEmpty())
        <section class="mb-12" aria-labelledby="experiences-heading">
            <h2 id="experiences-heading" class="mb-6 text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">
                {{ __('cv.experiences') }}
            </h2>

            <div class="space-y-8">
                @foreach($experiences as $experience)
                <article class="relative pl-6 before:absolute before:left-0 before:top-2 before:h-2 before:w-2 before:rounded-full before:bg-gray-400">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between">
                        <h3 class="font-medium text-gray-900">{{ $experience->titre_poste }}</h3>
                        <time class="shrink-0 text-sm text-gray-500" datetime="{{ $experience->date_debut->format('Y-m') }}">
                            {{ $experience->date_debut->translatedFormat('M Y') }}
                            —
                            {{ $experience->date_fin ? $experience->date_fin->translatedFormat('M Y') : __('cv.present') }}
                        </time>
                    </div>
                    <p class="text-sm font-medium text-gray-600">{{ $experience->entreprise }}</p>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $experience->description }}</p>

                    @if($experience->technologies)
                    <ul class="mt-3 flex flex-wrap gap-2" aria-label="{{ __('cv.technologies') }}">
                        @foreach($experience->technologies as $tech)
                        <li class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700">{{ $tech }}</li>
                        @endforeach
                    </ul>
                    @endif
                </article>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Formations --}}
        @if($formations->isNotEmpty())
        <section class="mb-12" aria-labelledby="formations-heading">
            <h2 id="formations-heading" class="mb-6 text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">
                {{ __('cv.formations') }}
            </h2>

            <div class="space-y-4">
                @foreach($formations as $formation)
                <article class="flex flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $formation->diplome }}</h3>
                        <p class="text-sm text-gray-600">{{ $formation->ecole }}</p>
                    </div>
                    <time class="shrink-0 text-sm text-gray-500" datetime="{{ $formation->annee }}">{{ $formation->annee }}</time>
                </article>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Compétences --}}
        @if($competences->isNotEmpty())
        <section class="mb-12" aria-labelledby="competences-heading">
            <h2 id="competences-heading" class="mb-6 text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">
                {{ __('cv.competences') }}
            </h2>

            <div class="space-y-5">
                @foreach($competences as $categorie => $items)
                <div>
                    <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">{{ $categorie }}</h3>
                    <ul class="flex flex-wrap gap-2" aria-label="{{ $categorie }}">
                        @foreach($items as $competence)
                        <li class="flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1 text-sm text-gray-700">
                            {{ $competence->nom }}
                            <span class="rounded-full px-2 py-0.5 text-xs
                                @if($competence->niveau === 'expert') bg-green-100 text-green-700
                                @elseif($competence->niveau === 'intermediaire') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-500
                                @endif"
                                aria-label="{{ __('cv.niveau_' . $competence->niveau) }}">
                                {{ __('cv.niveau_' . $competence->niveau) }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Langues --}}
        @if($langues->isNotEmpty())
        <section class="mb-12" aria-labelledby="langues-heading">
            <h2 id="langues-heading" class="mb-6 text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">
                {{ __('cv.langues') }}
            </h2>

            <ul class="flex flex-wrap gap-4">
                @foreach($langues as $langue)
                <li class="flex flex-col items-center gap-1 rounded-lg border border-gray-200 bg-white px-5 py-3 text-center">
                    <span class="font-medium text-gray-900">{{ $langue->langue }}</span>
                    <span class="text-xs text-gray-500">{{ $langue->niveau }}</span>
                </li>
                @endforeach
            </ul>
        </section>
        @endif

        {{-- Centres d'intérêt --}}
        @if($centresInteret->isNotEmpty())
        <section class="mb-12" aria-labelledby="interets-heading">
            <h2 id="interets-heading" class="mb-6 text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">
                {{ __('cv.centres_interet') }}
            </h2>

            <ul class="flex flex-wrap gap-2">
                @foreach($centresInteret as $interet)
                <li class="rounded-full bg-gray-100 px-4 py-2 text-sm text-gray-700">
                    {{ $interet->libelle }}
                </li>
                @endforeach
            </ul>
        </section>
        @endif

    </main>

</body>
</html>
