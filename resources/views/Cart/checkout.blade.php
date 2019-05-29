@extends('layouts.static_page')
@section('content')
    <div class="container give-me-space">
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
                                <h4 class="nomargin"> <a class=" my-link" href="{{route('index.product', ['id'=>$item->macma_id, 'product' => \App\Services\Help::slugify($item->name)])}}">{{$item->name}}</a></h4>
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

                <td colspan="4">
                    <div class="text-center">
                        <h3>Do zapłaty <span id="totalPrice">@if(empty($cart->totalPrice))0.00 zł @else{{number_format($cart->totalPrice, 2)}}zł @endif</span>zł</h3>
                    </div>
                </td>
                <td colspan="1">
                    <a class="btn my-button-border" href="{{url('/koszyk')}}">Zobacz koszyk <span class="fa fa-shopping-cart"></span></a>
                </td>
            </tr>
            </tbody>




        </table>
        <form onsubmit="$('.loader').removeClass('hide')" action="{{route('store.order')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="price" value="{{$cart->totalPrice}}">
        <div class="text-center col-sm-12">
            <h3>Wybierz dostawę</h3>
            <select data-totalPrice="{{$cart->totalPrice}}" class="form-control" name="delivery_id" required>
                @foreach($deliveries as $delivery)
                <option  data-price="{{$delivery->price}}" value="{{$delivery->id}}">{{$delivery->name}} ({{number_format($delivery->price, 2)}}zł)</option>
                    @endforeach
            </select>
            <hr>
        </div>
            <input type="hidden" id="payment_type" name="payment_type">
            <div class="col-sm-12 text-center">
                <h3>Metoda płatności: </h3>
                <hr>
                <div class="col-sm-12 row">
                    <div class="col-sm-4 row payment_type p-1" data-payment_val = "paypal">
                        <div class="col-sm-5 d-flex align-items-center" >
                            <img src="https://fidoalliance.org/wp-content/uploads/paypal_2014_logo_detail-1.png" class="w-100">
                        </div>
                        <div class="col-sm-7 d-flex align-items-center">
                            <p>Płatność przez paypal</p>
                        </div>
                    </div>
                    <div class="col-sm-4 row payment_type p-1" data-payment_val = "self_transfer">
                        <div class="col-sm-5 d-flex align-items-center">
                            <img src="https://dolcemoda.pl/public/assets/moduly/pobrane.png" class="w-100">
                        </div>
                        <div class="col-sm-7 d-flex align-items-center">
                            <p>Samodzielny przelew</p>
                        </div>
                    </div>
                </div>
            </div>
            
        <div class="text-center col-sm-12 mt-2">
            <h3>Twoje dane</h3>
            <hr>
        </div>
            <div class="form-group row">
                <label for="name" class="col-2 col-form-label">Imię i nazwisko:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Imię i nazwisko" name="name" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-2 col-form-label">Ulica i numer:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="ulica" name="street" required>
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="street_number" required>
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control" name="flat_number">
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-2 col-form-label">kod pocztowy:</label>
                <div class="col-sm-10">
                    <input type="text" pattern="[0-9]{2}\-[0-9]{3}" class="form-control" placeholder="00-000" name="postal_code" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-2 col-form-label">miasto:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="miasto" name="city" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-2 col-form-label">telefon:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" pattern="[0-9]{9}" placeholder="Wpisz swój numer telefonu" name="phone_number" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-2 col-form-label">email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" placeholder="przyklad@gmail.com" name="email" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-2 col-form-label">uwagi:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="comments" placeholder="Dodatkowe informację na temat zamówienia"></textarea>
                </div>
            </div>
            <button class="btn my-button" type="submit"> <span class="fa fa-check"></span> Zamawiam i płacę</button>
        </form>
    </div>

    @endsection

@section('page-title') Płatność @endsection
@section('page-description') Złóż zamówienie, oraz dokonaj płatności @endsection