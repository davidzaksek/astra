<!doctype html>
<html {!! get_language_attributes() !!}>

    @include('partials.head')

    <body id="top-scroll dark-bg" @php body_class() @endphp>

        @php do_action('get_header') @endphp
        @include('partials.header')

        <main class="main dark-bg">

            @yield('content')

        </main>

        @php do_action('get_footer') @endphp
        @include('partials.footer')
        @include('partials/custom/video-dialog')
        @php wp_footer() @endphp
        
        <div class="bg-lines"><div class="m-lines"></div></div>

    </body>
</html>
