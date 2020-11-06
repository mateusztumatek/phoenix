@extends('layouts.static_page')

@section('content')
    <h1 style="width: 0px; height: 0px; opacity: 0"> Ręcznie robiona biżuteria Raccmoon-craft.pl</h1>
 {{--   <div style="background-color: black" class="column-grid">
        <div class="column" id="column1">
            <img src="{{url('/default/final-3.jpg')}}">
            <div class="overlay">
                <h4>Twoja kolekcja</h4>
                <p> Lorem ipsum el va. Ano do</p>
                <a class="btn my-button"> Zobacz więcej</a>
            </div>
        </div>
        <div class="column" id="column2">
            <img src="{{url('/default/final-3.jpg')}}">
            <div class="overlay">

            </div>
        </div>
        <div class="column" id="column3">
            <img src="{{url('/default/final-3.jpg')}}">
            <div class="overlay">

            </div>
        </div>
        <div class="column" id="column4">
            <img src="{{url('/default/final-3.jpg')}}">
            <div class="overlay">

            </div>
        </div>
    </div>--}}
    <div class="container give-me-space">
        <h2 class="text-center header1"> TOP PRODUKTY </h2>
        <div class="row">
            <div style="max-width: 500px" id="product_slider">

                @foreach($products as $product)
                    <div class="product-slide">
                        <img class="w-100" src="{{$product->getProductImages()[0]}}">
                        <div class="content">

                            <h2 class="header">{{$product->name}}</h2>
                            <p class="description">{{$product->intro}}</p>
                            @php
                                $price = explode('.', number_format($product->price, 2));

                            @endphp
                            <p class="price">{{$price[0]}}.<span class="small">{{$price[1]}} zł</span></p>
                            <a style="cursor: pointer" href="{{url('/produkt/'.$product->id.'/'.\App\Services\Help::slugify($product->name))}}" class="my-button white-color">Zobacz produkt</a>
                        </div>
                    </div>
                    @endforeach
            </div>

        </div>
        <div class="sm-hidden">
            <a class="btn my-button-border w-100" href="{{url('/produkty')}}"> Zobacz wszystkie produkty</a>
        </div>
    </div>
    @php
        $static_banner = \App\Po::where('type', 'appearance_static_banner')->first();
    @endphp
    @if(isset($static_banner))
        @include('layouts.static_banner')
    @endif
    @php
    $services = \App\Po::where('type', 'appearance_services')->get();
    @endphp
    </div>
    @if(!$services->isEmpty())
    <div class="container">
        <div class="row justify-content-center give-me-space">
            <h2 class="text-center header1">Wszystko z pasją</h2>

            <section class="iq-features">
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-12"></div>
                        <div class="col-lg-8 col-md-12">
                            <div class="holderCircle">
                                <div class="round"></div>
                                <div class="dotCircle">
                                    @php
                                        $key = 1;
                                        @endphp
                                    @foreach($services as $service)
                                        @php
                                            $images = json_decode($service->image);
                                        @endphp
                           <span class="itemDot @if($key == 0) active @endif itemDot{{$key}}" data-tab="{{$key}}">
                           <img class="lazy" data-src="{{url('/str/'.$images[0])}}">

                           <span class="forActive"></span>

                           </span>
                                        @php
                                            $key++;
                                            @endphp
                                    @endforeach
                                </div>
                                <div class="contentCircle">
                                    @php
                                        $key = 1;
                                    @endphp
                                    @foreach($services as $service)
                                        @php
                                            $images = json_decode($service->image);
                                        @endphp
                                    <div class="CirItem title-box @if($key == 1) active @endif CirItem{{$key}}">
                                        <img class="background-image lazy" data-src="{{url('/str/'.$images[0])}}">
                                        <h2 class="title text-left"><span>{{$service->name}}</span></h2>

                                        <div class="CirItemDescription">
                                            <p>{!! $service->content !!}</p>

                                        </div>
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                        @php
                                            $key++;
                                        @endphp
                                        @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12"></div>
                    </div>
            </section>
        </div>
        <div class="row">
            <div style="max-width: 500px" id="gallery_slider">

                @foreach($gallery as $item)
                    <div class="product-slide">
                        <img class="gallery-image lazy" data-src="{{url('/str/'.$item->image)}}">
                        <div class="content">

                            <h2 class="header">{{$item->name}}</h2>
                            <div class="d-flex justify-content-center">
                                @if($item->facebook)
                                    <p class=" m-3 text-center" style="margin-bottom: 60px !important;">
                                        <a href="{{$item->facebook}}" target="_BLANK"><img data-src="{{url('/default/fb-icon.png')}}" style="max-width: 40px" class="icon-social lazy"></a>
                                    </p>
                                @endif
                                @if($item->instagram)
                                    <p class=" m-3 text-center" style="margin-bottom: 60px !important;">
                                        <a href="{{$item->instagram}}" target="_BLANK"><img data-src="{{url('/default/ig-icon.png')}}" style="max-width: 40px" class="icon-social lazy"></a>
                                    </p>
                                @endif
                                @if($item->site)
                                    <p class=" m-3 text-center" style="margin-bottom: 60px !important;">
                                        <a href="{{$item->site}}" target="_BLANK"><img data-src="{{url('/default/site-icon.png')}}" style="max-width: 40px" class="icon-social lazy"></a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <md-button  href="/galeria" class="md-raised md-primary white-color m-0 mt-3 w-100">Zobacz galerę zdjęć</md-button>
        </div>
        @endif
        <div class="page-content">
            @php
                if($page){
                    echo html_entity_decode($page->content);
                }
            @endphp
        </div>
    </div>
    @endsection
@if($page)
@section('page-title')
    {{$page->name}}
@endsection
@section('page-description')
    {{$page->description}}
@endsection
@else
@section('page-title')
    Raccmoon-craft ręcznie robiona biżuteria
@endsection

@section('page-description')
    Biżuteria Handmade, kolekcje Fanbazowe, rękodzielnictwo. Zamów swoją ulubioną biżuterię.
@endsection
    @endif
