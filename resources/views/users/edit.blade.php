@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <form action="{{ route('users.update',$user->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        @include('users.form')

    </form>

</div>

@endsection
