{{--
  Template Name: Chapter Template
--}}

@extends('layouts.app')

@section('content')
  
    <div class="chapters-section content-pages dark-bg">
        <div class="inner-child">

            {{-- Breadcrumbs --}}
            @php if ( function_exists('yoast_breadcrumb') ) yoast_breadcrumb( '<p id="breadcrumbs">','</p>' ); @endphp
        
            {{-- Partials --}}
            @include('partials/custom/page-chapters')

        </div>
    </div>

@endsection
