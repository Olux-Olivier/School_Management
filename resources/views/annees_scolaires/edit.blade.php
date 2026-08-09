@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <form action="{{ route('annees.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        @include('annees_scolaires.form')

    </form>

</div>

@endsection
