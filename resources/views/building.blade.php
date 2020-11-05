@if(\App\Services\Help::isMobile())
@include('mobile')

@else
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
    <meta name="description" content="@yield('page-description')">
    <meta name="google-site-verification" content="ww9g9RHRQpHGbZUYaWbbEMIDLsiV5EBlZtwKdSPp4n4" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}" type="text/css">

    <link rel="stylesheet" href="{{ asset('css/aos.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/css.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/product.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/component.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/gallery.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/carousel.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/contact_form.css') }}" type="text/css">
    <link rel="stylesheet" href="{{asset('css/loader.css')}}" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick-theme.css')}}"/>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Styles -->


</head>
<body>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v3.3"></script>

<div id="app">
    <div class="w-100 d-flex justify-center" style="min-height: 100vh; align-items: center">
        <div class="w-100">
            <div class="container">
                <div class="col-xl-5 mx-auto text-center">
                    <img src="{{url('/').'/str/'.setting('site.logo')}}" class="logo">
                    <h2 style="color:white" class="mt-5">Strona w przebudowie</h2>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

@yield('scripts_before')

<script type="text/javascript">
    var base_url = '{{\Illuminate\Support\Facades\URL::to('/')}}';
</script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<script src="{{asset('js/app.js')}}?hash=gwagawgawa"></script>
</html>
@endif