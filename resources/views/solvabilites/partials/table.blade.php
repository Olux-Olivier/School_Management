<div class="bg-white border border-slate-200 rounded-2xl shadow-sm mb-8 overflow-hidden">

    <div class="px-6 py-4 border-b border-slate-200">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-lg font-semibold
                    @if($couleur === 'green')
                        text-green-700
                    @elseif($couleur === 'yellow')
                        text-yellow-700
                    @else
                        text-red-700
                    @endif
                ">
                    {{ $titre }}
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    {{ $liste->count() }} élève(s)
                </p>

            </div>

        </div>

    </div>


    @if($liste->count())

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr>

                        <th class="px-5 py-3 text-left">
                            N°
                        </th>

                        <th class="px-5 py-3 text-left">
                            Matricule
                        </th>

                        <th class="px-5 py-3 text-left">
                            Élève
                        </th>

                        <th class="px-5 py-3 text-left">
                            Classe
                        </th>

                        <th class="px-5 py-3 text-right">
                            Montant dû
                        </th>

                        <th class="px-5 py-3 text-right">
                            Montant payé
                        </th>

                        <th class="px-5 py-3 text-right">
                            Restant
                        </th>

                        <th class="px-5 py-3 text-center">
                            Statut
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @foreach($liste as $index => $ligne)

                        <tr class="hover:bg-slate-50">

                            <td class="px-5 py-3">
                                {{ $index + 1 }}
                            </td>


                            <td class="px-5 py-3 font-medium">

                                {{ $ligne->eleve->matricule ?? '-' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $ligne->eleve->nom ?? '' }}
                                {{ $ligne->eleve->postnom ?? '' }}
                                {{ $ligne->eleve->prenom ?? '' }}

                            </td>


                            <td class="px-5 py-3">

                                {{ $ligne->classe->nom_complet
                                    ?? $ligne->classe->nom
                                    ?? '-' }}

                            </td>


                            <td class="px-5 py-3 text-right">

                                {{ number_format(
                                    $ligne->montant_du,
                                    2,
                                    ',',
                                    ' '
                                ) }}

                            </td>


                            <td class="px-5 py-3 text-right text-green-600 font-medium">

                                {{ number_format(
                                    $ligne->montant_paye,
                                    2,
                                    ',',
                                    ' '
                                ) }}

                            </td>


                            <td class="px-5 py-3 text-right text-red-600 font-medium">

                                {{ number_format(
                                    $ligne->restant,
                                    2,
                                    ',',
                                    ' '
                                ) }}

                            </td>


                            <td class="px-5 py-3 text-center">

                                @if($ligne->statut === 'En ordre')

                                    <span class="inline-flex px-3 py-1 rounded-full
                                        bg-green-100 text-green-700
                                        text-xs font-semibold">
                                        En ordre
                                    </span>

                                @elseif($ligne->statut === 'Partiellement payé')

                                    <span class="inline-flex px-3 py-1 rounded-full
                                        bg-yellow-100 text-yellow-700
                                        text-xs font-semibold">
                                        Partiellement payé
                                    </span>

                                @else

                                    <span class="inline-flex px-3 py-1 rounded-full
                                        bg-red-100 text-red-700
                                        text-xs font-semibold">
                                        Non en ordre
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="px-6 py-10 text-center text-slate-500">

            Aucun élève dans cette catégorie.

        </div>

    @endif

</div>
