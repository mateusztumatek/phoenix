@extends('layouts.static_page')

@section('content')
    <div class="container give-me-space">
        <div class="row m-0 justify-content-center">
            <h1 class="header1 w-100">Zamówienie nr. {{$order->id}}</h1>
            <order-component :order = "{{json_encode($order)}}"></order-component>
            @php

                   $cart = unserialize($order->cart);

            @endphp
            <table class="table table-dark ">
                <thead>
                <tr>
                    <th scope="col">Atrybut</th>
                    <th scope="col">Wartość</th>
                </tr>
                </thead>
                <tbody>

            @foreach($order->getAttributes() as $key => $value)
                    @if($value != null && $key != 'id' && $key != 'images' && $key != 'hash' && $key != 'updated_at' && $key != 'status' && $key != 'is_paid' && $key != 'cart')
                        <tr>
                            <td>
                                @switch($key)
                                    @case('comments')
                                        Komentarz
                                        @break
                                    @case('name')
                                        Imię i nazwisko
                                        @break
                                    @case('is_paid')
                                        Zapłacone
                                        @break
                                    @case('street')
                                        Ulica
                                        @break
                                    @case('street_number')
                                        Nr. ulicy
                                        @break
                                    @case('city')
                                        Miasto
                                        @break
                                    @case('postal_code')
                                        Kod pocztowy
                                        @break
                                    @case('email')
                                        Adres e-mail
                                        @break
                                    @case('delivery')
                                        Sposób dostarczenia
                                        @break
                                    @case('price')
                                        Całkowita cena zamówienia
                                        @break
                                    @case('created_at')
                                        Złożone dnia
                                        @break
                                    @endswitch
                            </td>
                            <td>

                                {{$value}}

                            </td>

                        </tr>
                        @endif

                @endforeach
                </tbody>

            </table>
            @if($cart)
                <h2 class="header1">Zakupione przedmioty</h2>
                <div class="row">
                    @foreach($cart->items as $item)
                    <div class="col-md-3 col-6 p-3">
                        <div class="position-relative w-100">
                            <a @if($item->type=='prdouct') href="{{url('/produkt/'.$item->id.'/'.\App\Services\Help::slugify($item->name))}}" @else href="{{url('/designs/'.$item->id)}}" @endif><img class="w-100" @if($item->type == 'product') src="{{url('/storage/'.json_decode($item->images)[0])}}" @else src="{{url('/storage/'.$item->previewImage)}}" @endif></a>
                            <div class="position-absolute w-100 d-flex justify-content-end align-content-end" style="bottom: 0px">
                                <div class="py-2 px-3 white-background" style="color: black; font-weight: bold">{{$item->quantity}}</div>
                            </div>
                        </div>
                    </div>
                        @endforeach
                </div>
                @endif

        </div>
    </div>


@endsection
@section('scripts_before')

@endsection
@section('scripts_after')


@endsection

@section('page-title') Zamówienie {{$order->id}} - Raccmoon-craft.pl
@endsection
@section('page-description')
@endsection
