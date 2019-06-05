{{--
@if(\Illuminate\Support\Facades\Session::has('saved'))
    @if(count(\Illuminate\Support\Facades\Session::get('saved')) != 0)
    <li class="nav-item active" id="saved">
        <a class="nav-link position-relative" id="favourites" data-toggle="dropdown"><i class="fa fa-heart color-white"></i><div class="count_fav bounceIn animated">{{count(\Illuminate\Support\Facades\Session::get('saved'))}}</div> </a>
        <ul role="menu" class="dropdown-menu" aria-label="favourites">
            <div class="m-auto dropdown-content row p-1 ">
                @foreach(\Illuminate\Support\Facades\Session::get('saved') as $key => $product)
                    <div class="w-25 product-slide">
                        <img src="{{$product->getProductImages()[0]}}" class="w-100">
                        <div class="content">
                            <h2 class="header">{{$product->name}}</h2>
                            @php
                                $price = explode('.', number_format($product->price, 2));

                            @endphp
                            <p class="price">{{$price[0]}}<span class="small">{{$price[1]}} zł</span></p>
                            <a style="cursor: pointer" data-product_id = "{{$product->id}}" data-slug = "{{\App\Services\Help::slugify($product->name)}}" onclick ="quick_view(this, event)" class="my-button">Zobacz produkt</a>
                            <form action="{{route('remove_favourite')}}" method="POST" onsubmit="send_favourite(this, event)">
                                @CSRF
                                <input type="hidden" name="product_id" value="{{$product->id}}">
                                <a style="cursor: pointer" class="my-button mt-2" onclick="$(this).parent().submit()">Usuń z ulubionych</a>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </ul>
    </li>
        @endif
@endif
--}}
