{{--
  Template Name: Content Template
--}}

@extends('layouts.app')

@section('content')
  
    <div class="general-content-page">
        <div class="inner-child narrow">

            <h1 class="h1"><u>{!! App::title() !!}</u></h1>

            {{-- Partials --}}
            @include('partials.content-page')

        </div>
    </div>

@endsection
