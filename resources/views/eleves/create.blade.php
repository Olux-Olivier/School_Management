@extends('layouts.app')

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
