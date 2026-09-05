@php
    $paiementDashboardActif = request()->routeIs('paiements.dashboard');
    $paiementJourActif = request()->routeIs('paiements.details-jour', 'paiements.edit');
    $paiementHistoriqueActif = request()->routeIs(
        'paiements.index',
        'paiements.show',
        'paiements.create'
    );
@endphp

<nav class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-sm"
     aria-label="Navigation des paiements">
    <div class="flex min-w-max gap-1 overflow-x-auto sm:min-w-0 sm:grid sm:grid-cols-3">
         <a href="{{ route('paiements.index') }}"
           @class([
               'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-medium transition',
               'bg-blue-600 text-white shadow-sm' => $paiementHistoriqueActif,
               'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => !$paiementHistoriqueActif,
           ])
           @if($paiementHistoriqueActif) aria-current="page" @endif>
            <i class="fas fa-users" aria-hidden="true"></i>
            <span>Élèves et historique</span>
        </a>
        <a href="{{ route('paiements.dashboard') }}"
           @class([
               'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-medium transition',
               'bg-blue-600 text-white shadow-sm' => $paiementDashboardActif,
               'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => !$paiementDashboardActif,
           ])
           @if($paiementDashboardActif) aria-current="page" @endif>
            <i class="fas fa-gauge-high" aria-hidden="true"></i>
            <span>Vue d’ensemble</span>
        </a>



        <a href="{{ route('paiements.details-jour') }}"
           @class([
               'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-medium transition',
               'bg-blue-600 text-white shadow-sm' => $paiementJourActif,
               'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => !$paiementJourActif,
           ])
           @if($paiementJourActif) aria-current="page" @endif>
            <i class="fas fa-calendar-day" aria-hidden="true"></i>
            <span>Transactions du jour</span>
        </a>
    </div>
</nav>
