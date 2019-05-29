@extends('layouts.static_page')

@section('content')
    <div class="container give-me-space">
        <div class="row m-0 justify-content-center">
            <h1 class="header1 w-100">Zamówienie nr. {{$order->id}}</h1>
            <order-component :order = "{{json_encode($order)}}"></order-component>
            @php
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
                    @if($value != null && $key != 'id' && $key != 'images' && $key != 'hash' && $key != 'updated_at' && $key != 'status' && $key != 'is_paid')
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
                                    @case('postal-code')
                                        Kod pocztowy
                                        @break
                                    @case('email')
                                        Adres e-mail
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
