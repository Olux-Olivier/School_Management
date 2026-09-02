@extends('layouts.app')

@section('title', 'Ajouter un élève')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <form action="{{ route('eleves.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        @include('eleves.form')

    </form>

</div>

@endsection
