@extends('layouts.app')

@section('title', 'Classes')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <form action="{{ route('annees.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        @include('classe.form')

    </form>

</div>

@endsection
