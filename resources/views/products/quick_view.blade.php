
<div class="modal fade product_view my-modal" id="product_view">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block">
                <a class="close-modal" data-dismiss="modal">X</a>
            </div>
            <div class="modal-body">
                <div class="row">
                    @if(isset($prod))

                    <div class="col-md-4 product_img SlideInLeft delay-200">

                        {{--<figure class="zoom" style="background-image: url('{{$product->getMainImage()}}')" onmousemove="zoom(event)">
                            <img src="{{$product->getMainImage()}}" class="img-responsive">
                        </figure>--}}
                        @php
                            $images = $prod->getProductImages();

                        @endphp
                        <div class="xzoom-holder">
                            <div class="xzoom-container">
                                <img onclick="window.open($(this).attr('src'), '_BLANK')" class="xzoom" id="xzoom-default" src="{{$images[0]}}" xoriginal="{{$images[0]}}" />
                                    @foreach($images as $k=>$image)
                                        @if($k >= 4)
                                            @break
                                        @endif
                                            <a href="{{$image}}">
                                                <img class="xzoom-gallery" width="80" src="{{$image}}" xpreview="{{$image}}">
                                            </a>

                                    @endforeach
                            </div>
                        </div>

                    </div>

                        <div class="col-md-8 product_content SlideInRight delay-400" id="product_modal_content">
                                <div class="revert">
                                    <div class="container">
                                        <div class="row justify-content-between m-0 ">
                                            <h3 class="m-0">{{$prod->name}}</h3>
                                                <div class="d-flex">
                                                    <span class="@if($prod->prices_sellout) line-through @endif">{{explode('.', number_format( $prod->profit_price, 2))[0]}}.<span class="price-small" style="font-size: 1rem">{{explode('.', number_format( $prod->profit_price, 2))[1]}}zł</span></span>
                                                    @if($prod->prices_sellout)
                                                        <span class="ml-2">{{explode('.', number_format( $prod->prices_sellout, 2))[0]}}.<span class="price-small" style="font-size: 1rem">{{explode('.', number_format( $prod->prices_sellout, 2))[1]}}zł</span></span>
                                                    @endif
                                                </div>
                                        </div>

                                    </div>
                                </div>
                            <div class="container pt-4 pb-4">
                                <p> <strong>{{$prod->intro}}</strong></p>
                                <p> <strong>Opis:</strong>{!! $prod->content !!}</p>
                                <p><span class="description">Waga: {{$prod->weight}}</span></p>
                                @php
                                    $sizes = explode(',', $prod->size);
                                @endphp
                                <div class="d-flex mb-1">
                                    <p><span class="description">Rozmiary:</span></p>
                                    <div class="ml-2">
                                        @foreach($sizes as $size)
                                            <p class="m-0"><span class="description">{{$size}}</span></p>
                                        @endforeach
                                    </div>


                                </div>

                                <p><span class="description">Materiały: @foreach($prod->getMaterials() as $material){{$material->name}},  @endforeach</span></p>
                                @if($prod->color)
                                    <p><span class="description">Kolor: {{$prod->color}}</span></p>
                                    @endif
                                @if($prod->quantity == 0)
                                <p class="text-center"><strong class="description"> Ten produkt nie jest dostępny w tej chwili, żeby dowiedzieć się więcej wyślij wiadomość</strong></p>
                                @else

                                <p><strong class="description">Dostępnych sztuk: {{$prod->quantity}} </strong></p>
                                    @endif


                                <form action="{{route('store.order')}}" onsubmit="sendform(this, event)" method="POST" class="mt-3 mb-3" id="send_order_mail">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="product_id" value="{{$prod->id}}">
                                    <p class="text-center"><strong>Zapytaj o ten produkt: </strong></p>
                                    <textarea name="text" placeholder="Tu wpisz treść wiadomości" class="form-control" rows="4" required></textarea>
                                    <div class="form-group row mt-2">
                                        <label for="staticEmail" class="col-sm-2 col-form-label vertical-align">Twój E-mail</label>
                                        <div class="col-sm-10">
                                            <input type="email" name="email" class="form-control-plaintext" id="staticEmail" placeholder="Podaj swój adres e-mail, na który ma zostać wysłana odpowiedź np jankowalski@gmail.com" required>
                                        </div>
                                    </div>

                                    <button type="submit" style="font-size: 1.1rem !important;" class="btn my-button w-100">Wyślij wiadomość</button>
                                    <button class="btn my-button mt-2 w-100" onclick="copyLink(this, event)" data-tip="Skopiuj link" title="Skopiuj link" href="{{$prod->getLink()}}">Kopiuj link produktu</button>

                                </form>

                            </div>

                        </div>

                    @endif
                    @if(isset($prod))
                    <script>
                        $(document).ready(function () {
                            $('.xzoom, .xzoom-gallery').xzoom();
                        })
                    </script>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts_after')
<script type="text/javascript">

    $(document).ready(function () {

        @if(isset($prod))
         $('#product_view').modal();
                @endif
        $('#send_order_mail').on('submit', function (event){
            event.preventDefault();
            var dat = new FormData($(this));

        })
    });
    @if(isset($prod))
    $(".xzoom, .xzoom-gallery").xzoom();

    @endif

</script>
    @endsection
