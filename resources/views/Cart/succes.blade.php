@extends('layouts.static_page')
@section('content')
    <div class="container give-me-space">
        <div class="col-sm-12 text-center mb-2">
            <h2> Twoje zamówienie: <strong>ID: {{$order->id}}</strong> </h2>
        </div>
        <table id="cart" class="table table-hover table-condensed">
            <thead>
            <tr>
                <th style="width:50%">Produkt</th>
                <th style="width:10%">Cena</th>
                <th style="width:8%">Ilość</th>
                <th style="width:8%">Kolor</th>
                <th style="width:22%" class="text-center">Cena Całkowita</th>
                <th style="width:10%"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($cart->items as $key => $item)
                <tr>
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-2 hidden-xs"><img src="{{$item->getMainImage()}}" alt="product-photo" class="img-responsive mw-100 mh-100"/></div>
                            <div class="col-sm-10">
                                <h4 class="nomargin"> <a class=" my-link" href="{{route('index.product', ['id' => $item->macma_id, 'product' => \App\Services\Help::slugify($item->name)])}}">{{$item->name}}</a></h4>
                                <p>{{substr($item->intro, 0, 90)}}...</p>
                                @if($item->mark_price > 0)
                                    Znakowanie: <strong>{{$item->getMark()->name}}</strong>
                                    <p>Twój projekt:</p>
                                    <img src="{{url('').'/'.$item->project_url}}" style="max-width: 100px; max-height: 100px">
                                @endif
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">{{number_format($item->profit_price, 2)}}</td>
                    <td data-th="Ilość">
                        {{$item->quanity}}
                    </td>
                    <td data-th="Kolor">
                        <div class="text-center color-container" style="opacity: 1 !important; position: relative !important;">
                            <ul class="d-flex justify-content-center" style="margin: 0px">
                                <li style="background-color: {{'#'.$item->colour}}" class="product-color"></li>
                            </ul>
                        </div>
                    </td>
                    @if($item->mark_price > 0)
                        <td data-th="Subtotal" class="text-center">{{number_format(($item->profit_price * $item->quanity) + ($item->mark_price * $item->quanity), 2)}}</td>
                    @else
                        <td data-th="Subtotal" class="text-center">{{number_format($item->profit_price * $item->quanity, 2)}}</td>
                    @endif

                </tr>
            @endforeach
            <tr style="width: 100%;">

                <td colspan="5">
                    <div class="text-center">
                        <h3>Cena całkowita <spanw>@if(empty($cart->totalPrice))0.00 zł @else{{number_format($cart->totalPrice, 2)}}zł @endif</spanw>zł</h3>
                    </div>
                </td>

            </tr>
            </tbody>




        </table>
        <div class="col-sm-12 text-center mb-2">
            <h2>Dane wysyłki: </h2>
        </div>
        @if($order->payment_state == 'approved')
        <div class="col-sm-12 d-flex justify-content-center mb-2">
            <div class="col-sm-7 ">
                <table class="table" id="order-table">
                    <tbody>

                    <tr>
                        <td scope="row">Rodzaj wysyłki</td>
                        <td>{{$order->getDelivery()->name}}</td>
                    </tr>
                    <tr>
                        <td scope="row">Imię i nazwisko</td>
                        <td>{{$order->name}}</td>
                    </tr>
                    <tr>
                        <td scope="row">Adres e-mail</td>
                        <td>{{$order->email}}</td>
                    </tr>
                    <tr>
                        <td scope="row">Numer telefonu</td>
                        <td>{{$order->phone_number}}</td>
                    </tr>
                    <tr>
                        <td scope="row">Miasto</td>
                        <td>{{$order->city}}</td>
                    </tr>
                    <tr>
                        <td scope="row">Kod pocztowy</td>
                        <td>{{$order->postal_code}}</td>
                    </tr>
                    <tr>
                        <td scope="row">Ulica</td>
                        <td>{{$order->street}}</td>
                    </tr>

                    <tr>
                        <td scope="row">Numer domu</td>
                        <td>{{$order->street_number}}</td>
                    </tr>
                    @if($order->flat_number)
                        <tr>
                            <td scope="row">Numer mieszkania</td>
                            <td>{{$order->flat_number}}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                    <div class="col-sm-12 text-center">
                       <p>Twoja płatność została zrealizowana:</p>
                        <p>metoda płatności: <strong>{{$order->payment_method}}</strong></p>
                        <p>numer płatności: <strong>{{$order->payment_id}}</strong></p>
                        <p>opłacono: <strong>{{substr($order->payment_create_time, 0, 10)}}</strong></p>
                    </div>

            </div>

        </div>
            @else
            <div class="col-sm-12 text-center">
                <p>Odbiór osobisty we Wrocławiu przy ulicy Traugutta 135/1A</p>
            </div>
        @endif
        <div class="col-sm-12 text-center">
            <h2><strong>Dziękujemy za złożenie zamówienia</strong></h2>
        </div>
    </div>

    @endsection