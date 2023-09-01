<!doctype html>
<html {!! get_language_attributes() !!}>

    @include('partials.head')

    <body id="top-scroll" @php body_class() @endphp>

        @php do_action('get_header') @endphp
        @include('partials.header')

        <main class="main">

            @yield('content')

            @php if (!in_array('page-doniraj-data', get_body_class()) && !in_array('page-zahvala-data', get_body_class()) ) : @endphp
                @include('partials.custom.instruktor-global')
            @php endif; @endphp

        </main>

        @php do_action('get_footer') @endphp
        @include('partials.footer')
        @include('partials/custom/video-dialog')
        @php wp_footer() @endphp
        
        <div class="bg-lines"><div class="m-lines"></div></div>

    </body>
</html>
