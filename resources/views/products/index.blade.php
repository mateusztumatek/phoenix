@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-12 text-center">

                {{--<img src="{{$product->getMainImage()}}" class="mw-75 mx-75">--}}



                    <div class="feature">
                        <figure class="featured-item image-holder r-3-2 transition"></figure>
                    </div>

                    <div class="gallery-wrapper">
                        <div class="gallery">

                            @foreach($product->getProductImages() as $key => $url)
                                {{$key}}
                                <div class="item-wrapper">
                                    <figure class="gallery-item image-holder r-3-2 @if($key == 0) active @endif transition"></figure>
                                </div>
                                @endforeach

                        </div>
                    </div>

                    <div class="controls">
                        <button class="move-btn left">&larr;</button>
                        <button class="move-btn right">&rarr;</button>
                    </div>




                </div>

            <div class="col-md-6 col-sm-12 product-content d-flex">
                <form class="add-to-cart_form" onsubmit="sendform(this, event)" action="{{route('add_item_to_cart')}}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="product_id" value="{{$product->macma_id}}">
                    <div class=" text-center mt-5">
                        <h1 style="display:none">{{$product->name}} z uniwersalnym nadrukiem</h1>
                        <h2>{{$product->name}}</h2>
                        <h4 class="price">{{number_format($product->profit_price, 2)}}<span class="ml-2" style="font-size: 0.8rem;">PLN</span></h4>
                        <p class="m-3">{{$product->intro}}</p>

                        <div class="form-group row justify-content-center">
                            <label for="name" class="col-2 col-form-label">Ilość:</label>
                            <input id="quanity" type="number" class="form-control col-sm-5" name="quanity" value="1" required>
                        </div>
                        <button type="submit" class="btn my-button-border mt-3" > Dodaj do koszyka </button>
                    </div>
                </form>
            </div>

        </div>

        <div id="accordion">
            <h3>Szczegóły <span style="opacity:0.2" class="float-right"><i class="fa fa-angle-double-down"></i></span></h3>
            <div>
                <table class="table table-striped w-50">
                    <thead>
                    <tr>
                        <th  scope="col">Typ</th>
                        <th scope="col">Wartość</th>
                    </tr>
                    </thead>
                    <tbody>


                    <tr>
                        <th scope="row">Rozmiary</th>


                        <td>{{$product->size}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Waga</th>
                        <td>{{$product->weight}}</td>
                    </tr>
                    <tr>
                        @if(!$product->getMaterials()->isEmpty())
                        <th scope="row">Materiały</th>
                        <td>
                            @foreach($product->getMaterials() as $material)
                                {{$material->name}}
                                @endforeach
                        </td>
                            @endif
                    </tr>

                    </tbody>
                </table>
            </div>
            @if($stock)
            <h3>Dostępność <span style="opacity:0.2" class="float-right"><i class="fa fa-angle-double-down"></i></span></h3>
            <div>
                <table class="table table-striped w-50">

                    <thead>
                    <tr>
                        <th  scope="col">Okres dostawy</th>
                        <th scope="col">Wartość</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">Dostępne w ciągu 24 godzin</th>
                        <td>
                            @if($stock->quantity_24h == 0)
                                -
                                @else
                                {{$stock->quantity_24h}}
                                @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Dostępne w ciągu 37 dni</th>
                        <td>
                            @if($stock->quantity_37days == 0)
                                -
                            @else
                                {{$stock->quantity_37days}}
                            @endif
                        </td>
                    </tr>
                    </tbody>

                </table>
            </div>
            @endif
            <h3  style=" border-bottom: 1px solid rgba(0,0,0,0.2) !important;">Oceń <span style="opacity:0.2" class="float-right"><i class="fa fa-angle-double-down"></i></span></h3>
            <div class="row justify-content-center">
                <div class="col-sm-10 text-center">
                    <form class="row justify-content-center" action="{{route('add.comment', ['product' => $product])}}" method="POST"  style="width: 100%;">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="col-sm-12 form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Twoje imię</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" placeholder="Te pole nie jest wymagane">
                            </div>
                        </div>

                            <div class="text-left">


                             <fieldset class="col-sm-12 rating">
                                 <input  type="radio" id="star5" name="rating" value="5" /><label class = "full" for="star5" title="Super"></label>
                                <input  type="radio" id="star4" name="rating" value="4" /><label class = "full" for="star4" title="Całkiem fajne"></label>
                                <input  type="radio" id="star3" name="rating" value="3" /><label class = "full" for="star3" title="Średnie"></label>
                                <input  type="radio" id="star2" name="rating" value="2" /><label class = "full" for="star2" title="Może być"></label>
                                <input  type="radio" id="star1" name="rating" value="1" /><label class = "full" for="star1" title="Słabe"></label>
                             </fieldset>
                            </div>
                        <div class="col-sm-12 form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Dodaj opis komentarza</label>
                            <div class="col-sm-10">
                                <textarea type="text" name="comment" class="form-control" placeholder="Te pole nie jest wymagane"> </textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="submit" class="btn my-button">Dodaj</button>
                        </div>
                    </form>

                </div>
                <div class="col-sm-12">
                    <hr>

                </div>
                @if($rates = $product->getRates())
                    <div class="container">

                    @foreach($rates as $rate)
                        <div class = "col-sm-12 row rate">
                            <div class="col-sm-8 rate-content">
                                @if($rate->author)
                                    <strong class="mb-2">{{$rate->author}}</strong>
                                @endif
                                @if($rate->description)
                                    <p>{{$rate->description}}</p>
                                @endif
                            </div>
                            <div class="col-sm-4 rate-description">
                                <div>
                                    <span>{{$rate->rate}}</span>
                                </div>
                            </div>
                        </div>

                    @endforeach
                    </div>
                    @endif



            </div>



        </div>

    </div>


    @endsection
@section('scripts_before')
    <script type="text/javascript">

        var images = [
            @foreach($product->getProductImages() as $url)
                '{{$url}}',
            @endforeach
        ], product_price = '{{$product->profit_price}}';

    </script>
@endsection
@section('scripts_after')
    <script src="{{asset('js/gallery.js')}}"></script>

    <script type="text/javascript">



       $(document).ready(function () {
            $('.featured-item').height('150px');
            $('#quanity').on('change', function () {

                console.log(parseFloat(product_price) * $(this).val())
            });

       });

    </script>
@endsection

@section('page-title') {{$product->name}} z nadrukiem - Firmowygadżet.pl
@endsection
@section('page-description')
@endsection
