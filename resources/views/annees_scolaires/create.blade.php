@extends('layouts.app')

@section('title', 'Ajouter une année scolaire')

@section('breadcrumb', 'Années scolaires / Ajouter')

@section('content')

<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

    <form action="{{ route('annees.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        @include('annees_scolaires.form')

    </form>

</div>

@endsection
