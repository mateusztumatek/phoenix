
@extends('layouts.static_page')
@section('content')
    @if($page)
    <div class="container give-me-space">
        <h2 class="text-center header1"><span class="before d-block">{{$page->description}}</span> {{$page->name}}</h2>
        <div class="page-content white-color">
            @php

                echo html_entity_decode($page->content);
            @endphp
        </div>
        @if($page->url == 'zamow')

        <div class="container give-me-space">
            <form action="{{route('store.order')}}" onsubmit="sendform(this, event)" method="POST" class="mt-3 mb-3" id="send_order_mail">
            @csrf
            <h2 class="header1 mb-3"> Wyślij zamówienie lub zapytanie</h2>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2 white-color">
                        Wiadomość
                    </div>
                    <div class="col-md-10">
                        <textarea class="form-control" rows="3" name="message" placeholder="Twoja wiadomość"></textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-2 white-color">
                        Adres email
                    </div>
                    <div class="col-md-10">
                        <input class="form-control" type="email" name="email" placeholder="example@gmail.com">

                    </div>
                </div>
            </div>
            <button type="submit" class=" btn my-button w-100">Wyślij</button>
            </form>

        </div>
            @endif
    </div>
    @endif
    @if($page->name == 'Pomoc')
        @include('contact_form')
    @endif
    @endsection


@section('scripts_before')
    <script type="text/javascript">



        </script>
    @endsection
@section('scripts_after')
    <script type="text/javascript">
        @if($page)
        var ht = '{{$page->content}}';
        @endif
        $(document).ready(function () {
            console.log(ht);

        });
    </script>
@endsection
@section('page-title') {{$page->name}} @endsection
@section('page-description') {{$page->description}} @endsection