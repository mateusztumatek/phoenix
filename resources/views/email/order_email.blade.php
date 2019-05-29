<style type="text/css">
    .body{
        text-align: center;
    }
    h1{
        font-size: 1.2rem;
        font-weight: bold;

    }
    .border{
        border: 1px solid rgba(118,118,118,0.65);
    }
    .padding{
        padding: 30px;
    }
    .padding-sm{
        padding: 7px;
    }
    .small-text{
        font-size: 0.8rem;
        color: rgba(40,40,40,0.65);
    }
    .text-center{
        text-align: center !important;
    }

    .w-100{
        width: 100%;
    }
</style>

<body>
    <div class="border padding">
        @if($product)
            <h1>Zapytanie od klienta do produktu: <a href="{{$product->getLink()}}" target="_BLANK">{{$product->name}}</a> </h1>

        @else
            <h1>Zamówienie od klienta</h1>

        @endif
        <p class="padding">{{$content}}</p>
        <p> Odpowiedz na: {{$email}}</p>
    </div>
</body>