@extends('layouts.admin')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <form action="{{ route('users.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        @include('users.form')

    </form>

</div>

@endsection
