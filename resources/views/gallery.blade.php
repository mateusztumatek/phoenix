@extends('layouts.static_page')

@section('content')
   {{-- @php
        $chunked = array_chunk($gallery->toArray(), count($gallery) / 4);
    dd($chunked);
    @endphp--}}
    <div class="container give-me-space">
        <h1 class="header1"><span class="before d-block">Chcesz żeby Twoje zdjęcie również znalazło się w Galerii? Wyślij je do mnie!</span> Galeria Waszych zdjęć</h1>
        <div class="mt-2 photos">

            @foreach($gallery as $key => $g)

                <div class="photo mt-2">
                    <img id="{{$key}}_image" src="{{url('/str/'.$g->image)}}">
                    <div class="content vertical-align" style="flex-wrap: wrap">
                        <div class="w-100">
                                <h2 class="w-100 text-center">{{$g->name}}</h2>

                                      <div class="d-flex justify-content-center">
                                          @if($g->facebook)
                                              <p class=" m-3 text-center" style="margin-bottom: 60px !important;">
                                                  <a href="{{$g->facebook}}" target="_BLANK"><img src="{{url('/default/fb-icon.png')}}" style="max-width: 40px" class="icon-social"></a>
                                              </p>
                                          @endif
                                          @if($g->instagram)
                                              <p class=" m-3 text-center" style="margin-bottom: 60px !important;">
                                                  <a href="{{$g->instagram}}" target="_BLANK"><img src="{{url('/default/ig-icon.png')}}" style="max-width: 40px" class="icon-social"></a>
                                              </p>
                                          @endif
                                          @if($g->site)
                                              <p class=" m-3 text-center" style="margin-bottom: 60px !important;">
                                                  <a href="{{$g->site}}" target="_BLANK"><img src="{{url('/default/site-icon.png')}}" style="max-width: 40px" class="icon-social"></a>
                                              </p>
                                          @endif
                                      </div>
                                 @if($g->product_id && $g->prod)

                                <div class="d-flex justify-content-center">
                                    <a  style="padding: 25px 10px !important; text-align: center !important;" class="my-button-border w-100 white-color" href="{{url('/produkt/'.$g->product_id.'/'.\App\Services\Help::slugify($g->prod->name))}}">zobacz produkt</a>

                                </div>
                                @endif
                                <div class="d-flex justify-content-center">
                                    <button onclick="window.location.href = $('#{{$key}}_image').attr('src')" style="padding: 25px 10px !important; text-align: center !important;" class="my-button-border w-100 mt-1"> Zobacz zdjęcie</button>
                                </div>
                        </div>

                    </div>
                </div>

            @endforeach
        </div>

    </div>
@endsection

@section('page-title')
    Galeria waszych zdjęć
@endsection
@section('page-description')
    Chcesz żeby twoje zdjęcie również było w tej galeri. Wystarczy że wyślesz je do mnie.
@endsection
