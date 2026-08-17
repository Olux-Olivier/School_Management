@extends('layouts.app')

@section('title', 'Nouvelle inscription')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-slate-700">
            Nouvelle inscription
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            Enregistrer l'inscription d'un élève.
        </p>

    </div>


    @include('inscriptions.form')

</div>

@endsection
