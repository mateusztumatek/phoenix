@php
    $cart = Session::has('cart')? Session::get('cart') : null;
    $menu_pages = \App\Page::where('top_menu_active', 1)->get();
    $brands = \App\Category::join('brands', 'pro_categories.name', 'brands.name')->select('pro_categories.*')->get();

@endphp

<div class="errors @if(\Illuminate\Support\Facades\Session::has('errors')) active-errors @elseif(\Illuminate\Support\Facades\Session::has('message')) active-errors  @endif">


    @if(\Illuminate\Support\Facades\Session::has('message'))
        <p>{{\Illuminate\Support\Facades\Session::get('message')}}</p>
    @endif


    @foreach($errors->all() as $error)
        <p>
            {{$error}}
        </p>

    @endforeach


</div>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top " style="position: fixed !important;">
        <div class="container">

        <a class="navbar-brand" href="{{route('home')}}">    <img src="{{url('/').'/str/'.setting('site.logo')}}" class="logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto ">

              {{-- <li class="nav-item active cl-effect-1">
                    <a class="nav-link" href="{{url('/')}}">Strona główna <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active cl-effect-1">
                    <a class="nav-link" href="{{route('items')}}">Produkty</a>
                </li>

                @foreach($menu_pages as $page)
                <li class="nav-item active cl-effect-1">
                    <a class="nav-link" href="{{url('/strona/'.$page->url)}}">{{$page->name}}</a>
                </li>
                @endforeach--}}
                {{menu('top_menu', 'vendor.voyager.mymenu')}}
                <div class="ml-4 d-flex">
                    <li class="nav-item">
                        <a href="https://www.facebook.com/raccmoon.craft/" target="_blank" class="nav-link pl-0 pr-0"><img class="icon-social" src="{{url('default/fb-icon.png')}}" style="max-width: 20px"></a>
                    </li>
                    <li class="nav-item">
                        <a href="https://www.instagram.com/raccmoon.craft/" target="_blank" class="nav-link pl-0 pr-0"><img class="icon-social" src="{{url('default/ig-icon.png')}}" style="max-width: 20px"></a>
                    </li>
                </div>

            </ul>

        </div>
        </div>

    </nav>

<cart v-on:change-cart="itemsChange" :cart="cart" v-on:my-event="show()" :showCart="showCart"></cart>
