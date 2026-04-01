<section class="space-y-10">
    <header class="mb-10">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-1 h-6 bg-status-rejected rounded-full"></div>
            <h2 class="text-xl font-display font-black text-primary tracking-tight italic">
                {{ __('Zone de Danger') }}
            </h2>
        </div>

        <p class="text-xs font-bold text-primary-muted leading-relaxed italic">
            {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Supprimer le Compte') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-12 space-y-10">
            @csrf
            @method('delete')

            <div class="space-y-4">
                <h2 class="text-2xl font-display font-black text-primary tracking-tight italic">
                    {{ __('Êtes-vous sûr de vouloir supprimer votre compte ?') }}
                </h2>

                <p class="text-xs font-bold text-primary-muted leading-relaxed italic">
                    {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez saisir votre mot de passe pour confirmer.') }}
                </p>
            </div>

            <div class="space-y-2">
                <x-input-label for="password" value="{{ __('Mot de passe') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full"
                    placeholder="{{ __('Confirmez avec votre mot de passe') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4">
                <x-secondary-button x-on:click="$dispatch('close')" class="w-full sm:w-auto justify-center">
                    {{ __('Annuler') }}
                </x-secondary-button>

                <x-danger-button class="w-full sm:w-auto justify-center">
                    {{ __('Confirmer la Suppression') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
