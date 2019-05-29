@extends('layouts.static_page')
@section('content')
    <div class="container">
        <table id="cart" class="table table-hover table-condensed">
            <thead>
            <tr>
                <th style="width:50%">Produkt</th>
                <th style="width:19%">Cena jednostkowa</th>
                <th style="width:8%">Ilość</th>
                <th style="width:8%">Kolor</th>
                <th style="width:22%" class="text-center" colspan="2">Cena całkowita</th>
                <th style="width:3%"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($cart->items as $key => $item)
            <tr>
                <td data-th="Product">
                    <div class="row">
                        <div class="col-sm-2 hidden-s"><img src="{{$item->getMainImage()}}" alt="product-photo" class="img-responsive mw-100 mh-100 "/></div>
                        <div class="col-sm-10">
                            <h4 class="nomargin"> <a class="my-link" href="{{route('index.product', ['id'=>$item->macma_id, 'product' => \App\Services\Help::slugify($item->name)])}}"> {{$item->name}}</a> </h4>
                            <p>{{substr($item->intro, 0, 90)}}...</p>
                            @foreach($item->getMarks() as $mark)

                               <p style="font-size: 0.8rem"> znakowanie: {{$mark->name}} dostępne od: {{$mark->min_quantity}} sztuk</p>
                                @endforeach
                            @if($item->mark > 0)
                                Znakowanie: <strong>{{$item->getMark()->name}}</strong>
                            <p>Twój projekt:</p>
                                <img src="{{url('').'/'.$item->project_url}}" style="max-width: 100px; max-height: 100px">
                            <form action="{{route('delete_mark', ['item' => $key])}}" method="POST">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <button type="submit" class="btn my-button" style="width: auto; font-size: 12px !important; padding: 5px !important;">Usuń znakowanie</button>
                            </form>

                            @else
                                <button onclick="get_item_Signs({{$key}})" class="btn my-button" style="width: auto; font-size: 12px !important; padding: 5px !important;">Dodaj znakowanie</button>

                            @endif
                        </div>
                    </div>
                </td>
                <td data-th="Price">{{number_format($item->profit_price, 2)}} @if($item->mark_price > 0) + cena znakowania: {{$item->mark_price * $item->quanity}}zł @endif </td>
                <td style="width: 13%" data-th="Quantity" class="quantity-row">
                    <input type="number" onchange="$('#'+$(this).attr('data-target')).val($(this).val())" data-target="{{$key}}_quanity" class="form-control text-left quanity_change_input" value="{{$item->quanity}}">
                </td>
                <td data-th="Kolor">
                    <div class="text-center color-container" style="opacity: 1 !important; position: relative !important;">
                        <ul class="d-flex justify-content-center" style="margin: 0px">
                                <li onclick = "getProductColors({{$key}})" style="background-color: {{'#'.$item->colour}}" class="product-color"></li>
                        </ul>
                    </div>
                </td>
                <td data-th="Subtotal" class="text-center" colspan="2">{{number_format($item->profit_price * $item->quanity + $item->mark_price * $item->quanity, 2)}}</td>
                <td class="actions" data-th="">
                    <form class="delete_item_from_cart" onsubmit="sendform(this, event)" action="{{route('delete_item_from_cart')}}" method="POST">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="item_id" value="{{$key}}">
                        <button type="submit" class="btn btn-sm bg-primary-color" style="border: none; color: white"><i class="fa fa-trash"></i></button>

                    </form>

                </td>
            </tr>
                @endforeach
            <form method="POST" id="refresh_cart" action="{{route('refresh.cart')}}">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                @foreach($cart->items as $key => $item)
                    <input type="hidden" id="{{$key}}_quanity" name="{{$key}}" value="{{$item->quanity}}">
                    @endforeach
            </form>
            </tbody>

            <tfoot>

            <tr>
                <td><a href="{{url('/')}}" class="btn my-button-border"><i class="fa fa-angle-left"></i> Kontynuuj zakupy</a></td>
                <td colspan="4" class="hidden-xs text-center"><strong>@if(empty($cart->totalPrice)) Całkowita cena 0.00 zł @else Całkowita cena {{number_format($cart->totalPrice, 2)}}zł @endif</strong></td>
                <td colspan="2"><a href="{{url('/checkout')}}" class="btn my-button-border float-right">Zapłać <i class="fa fa-angle-right"></i></a></td>
            </tr>
            </tfoot>
        </table>
    </div>
    @endsection

@section('page-title') Koszyk @endsection
@section('page-description') Tutaj znajdziesz wszystkie produkty które chcesz zamówić @endsection