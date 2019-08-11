<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-139204231-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-139204231-1');
    </script>

    <link rel="shortcut icon" href="{{url('/default/icona.ico')}}" />

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="ww9g9RHRQpHGbZUYaWbbEMIDLsiV5EBlZtwKdSPp4n4" />
    <meta name="description" content="@yield('page-description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">

    <link rel="stylesheet" href="{{ asset('css/aos.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/css.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/product.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/carousel.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/contact_form.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/component.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/admin-creator.css') }}" type="text/css">


    <link rel="stylesheet" href="{{asset('css/loader.css')}}" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick-theme.css')}}"/>
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/gallery.css') }}" type="text/css">


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Styles -->


</head>
<body>
<div id="app">

    <div class="site-loader">
        <div class="content">
            <img class="image-loader" src="{{url('default/szop-sam.png')}}">
        </div>
    </div>
    <div class="loader">
        <div class="content">
            <div class="spinner"></div>
        </div>
    </div>

    <div class="flex-center position-ref full-height">
        @if(strpos(url()->current(), 'strona') !== false)
            <div class="static-banner d-flex justify-content-start align-items-center give-me-space" >
                <img src="{{url('/str/'.$page->background)}}">

            </div>
        @else
            @if(Route::getCurrentRoute()->uri() == '/')
                @include('layouts.carousel')
            @else
                @php
                    $static_banner = \App\Po::where('type', 'appearance_static_banner')->first();
                @endphp
                @if(isset($static_banner))
                    @include('layouts.static_banner')
                @endif
            @endif
        @endif

        @include('layouts.top_navbar')

        @yield('content')

        @include('layouts.footer')

    </div>
    <div class="loader hide">
        <div class="lds-ripple"><div></div><div></div></div>
    </div>
    <div id="marks_modal">

    </div>
    <div id="colours_modal">

    </div>
    <a id="button" >
        <i class="fa fa-angle-double-up"></i>
    </a>
    @include('layouts.search_modal')
    @include('products.quick_view')
    <div id="thanks_modal"></div>
</div>
</body>

@yield('scripts_before')

<script type="text/javascript">
    var base_url = '{{\Illuminate\Support\Facades\URL::to('/')}}';
    function th(file, dataUrl) {
        var j, len, ref, thumbnailElement;
        if (file.previewElement) {
            file.previewElement.classList.remove("dz-file-preview");

            var elem = '<a class="dz-remove dz-add" href="javascript:undefined;">Add to project</a>';
            $(file.previewElement).append(elem);

            ref = file.previewElement.querySelectorAll("[data-dz-thumbnail-bg]");
            for (j = 0, len = ref.length; j < len; j++) {
                thumbnailElement = ref[j];
                thumbnailElement.alt = file.name;
                thumbnailElement.style.backgroundImage = 'url("' + dataUrl + '")';
            }
            return setTimeout(((function(_this) {
                return function() {
                    return file.previewElement.classList.add("dz-image-preview");
                };
            })(this)), 1);
        }
    }
</script>

<script src="{{asset('js/app.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{asset('js/custom.js')}}"></script>
<script src="{{asset('js/aos.js')}}"></script>

<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<script src="{{asset('js/modernizr.custom.js')}}"></script>
<script type="text/javascript" src="{{asset('slick/slick.js')}}"></script>
<script src='https://hammerjs.github.io/dist/hammer.min.js'></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
@yield('scripts_after')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</html>
