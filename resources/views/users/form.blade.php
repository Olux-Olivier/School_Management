<div class="bg-white rounded-lg shadow">

    <div class="border-b px-6 py-4">

        <h2 class="text-2xl font-bold text-gray-700">

            {{ isset($user) ? 'Modifier un utilisateur' : 'Ajouter un utilisateur' }}

        </h2>

    </div>

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nom -->
            <div>

                <label class="block mb-2 font-medium">
                    Nom <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="nom"
                    value="{{ old('nom', $user->nom ?? '') }}"
                    class="w-full border rounded-lg px-4 py-2">

                @error('nom')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror

            </div>

            <!-- Postnom -->
            <div>

                <label class="block mb-2 font-medium">
                    Postnom
                </label>

                <input
                    type="text"
                    name="postnom"
                    value="{{ old('postnom', $user->postnom ?? '') }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- Prénom -->
            <div>

                <label class="block mb-2 font-medium">
                    Prénom
                </label>

                <input
                    type="text"
                    name="prenom"
                    value="{{ old('prenom', $user->prenom ?? '') }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- Sexe -->
            <div>

                <label class="block mb-2 font-medium">
                    Sexe
                </label>

                <select
                    name="sexe"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="">Sélectionner</option>

                    <option value="M"
                        {{ old('sexe', $user->sexe ?? '') == 'M' ? 'selected' : '' }}>
                        Masculin
                    </option>

                    <option value="F"
                        {{ old('sexe', $user->sexe ?? '') == 'F' ? 'selected' : '' }}>
                        Féminin
                    </option>

                </select>

            </div>

            <!-- Téléphone -->
            <div>

                <label class="block mb-2 font-medium">
                    Téléphone
                </label>

                <input
                    type="text"
                    name="telephone"
                    value="{{ old('telephone', $user->telephone ?? '') }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- Email -->
            <div>

                <label class="block mb-2 font-medium">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email ?? '') }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- Username -->
            <div>

                <label class="block mb-2 font-medium">
                    Nom d'utilisateur
                </label>

                <input
                    type="text"
                    name="username"
                    value="{{ old('username', $user->username ?? '') }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- Type -->
            <div>

                <label class="block mb-2 font-medium">
                    Type
                </label>

                <select
                    name="type"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="Agent"
                        {{ old('type', $user->type ?? '') == 'Agent' ? 'selected' : '' }}>
                        Agent
                    </option>

                    <option value="Admin"
                        {{ old('type', $user->type ?? '') == 'Admin' ? 'selected' : '' }}>
                        Administrateur
                    </option>

                </select>

            </div>

            <!-- Statut -->
            <div>

                <label class="block mb-2 font-medium">
                    Statut
                </label>

                <select
                    name="actif"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="1"
                        {{ old('actif', $user->actif ?? 1) == 1 ? 'selected' : '' }}>
                        Actif
                    </option>

                    <option value="0"
                        {{ old('actif', $user->actif ?? 1) == 0 ? 'selected' : '' }}>
                        Inactif
                    </option>

                </select>

            </div>

            <!-- Photo -->
            <div>

                <label class="block mb-2 font-medium">
                    Photo
                </label>

                @if(isset($user) && $user->photo)

                    <div class="mb-3">

                        <img
                            src="{{ asset('storage/'.$user->photo) }}"
                            class="w-24 h-24 rounded-full object-cover border">

                    </div>

                @endif

                <input
                    type="file"
                    name="photo"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- Mot de passe uniquement à la création -->
            @if(!isset($user))

                <div>

                    <label class="block mb-2 font-medium">
                        Mot de passe
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full border rounded-lg px-4 py-2">

                </div>

                <div>

                    <label class="block mb-2 font-medium">
                        Confirmation
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="w-full border rounded-lg px-4 py-2">

                </div>

            @endif

        </div>

    </div>

    <div class="border-t px-6 py-4 flex justify-end gap-3">

        <a
            href="{{ route('users.index') }}"
            class="px-5 py-2 rounded bg-gray-300 hover:bg-gray-400">

            Annuler

        </a>

        <button
            type="submit"
            class="px-5 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">

            {{ isset($user) ? 'Mettre à jour' : 'Enregistrer' }}

        </button>

    </div>

</div>
