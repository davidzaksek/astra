@extends('layouts.app')

@section('content')

    @if (!have_posts())
        @include('partials/custom/404')
    @endif
  
@endsection