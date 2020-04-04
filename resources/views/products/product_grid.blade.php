@if(!$products->isEmpty())
    @foreach($products as $product)
        <div class="col-md-4 col-sm-6">
            <div data-aos="zoom-in" data-aos-duration="200" class="product-grid4 animated @if($product->quantity == 0) muted @endif border-muted">
                <div class="product-image4">
                    <div style="cursor: pointer" data-product_id = "{{$product->id}}" data-slug = "{{\App\Services\Help::slugify($product->name)}}" onclick ="quick_view(this, event)" class="overlay"></div>
                    <a data-product_id = "{{$product->id}}" data-slug = "{{\App\Services\Help::slugify($product->name)}}" onclick ="quick_view(this, event)" href="{{$product->getLink()}}">
                            <img class="pic-1" src="{{$product->getProductImages()[0]}}">
                    </a>
                    <div class="product-labels">
                    @if($product->new)
                     <span>Nowość</span>
                    @endif
                        @if($product->availability == 0)
                            <span>Niedostępny</span>
                        @endif
                        @if($product->availability == 1)
                            <span>Dostępny od ręki</span>
                        @endif
                        @if($product->availability == 2)
                            <span>Dostępny na zamówienie</span>
                        @endif
                    </div>
                </div>
                <div class="product-content">
                    <h2 class="title"><a href="#" style="cursor: pointer" data-product_id = "{{$product->id}}" data-slug = "{{\App\Services\Help::slugify($product->name)}}" onclick ="quick_view(this, event)">{{str_limit($product->name, 28)}}</a></h2>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <div class="price @if($product->prices_sellout) line-through @endif">
                                {{explode('.', number_format( $product->profit_price, 2))[0]}}.<span class="price-small" style="font-size: 1rem">{{explode('.', number_format( $product->profit_price, 2))[1]}}zł</span>
                            </div>
                            @if($product->prices_sellout)
                                <div class="price ml-3">
                                    {{explode('.', number_format( $product->prices_sellout, 2))[0]}}.<span class="price-small" style="font-size: 1rem">{{explode('.', number_format( $product->prices_sellout, 2))[1]}}zł</span>
                                </div>
                            @endif
                        </div>
                        <md-button @click="addToCart({{$product}})" class="md-icon-button  md-raised md-primar">
                            <i  class="fas fa-shopping-cart"></i>
                        </md-button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @if($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)

        @if($products->currentPage() % 2 == 0)
            <div class="col-sm-12 text-center mb-2 mt-2">
                <button class="btn my-button-border" onclick="show_more_products(this)">Zobacz więcej produktów</button>
            </div>
        @endif
        <!-- Holds your page information!! -->
        <input type="hidden" id="page" value="1" />
        <input type="hidden" id="max_page" value="{{$products->lastPage()}}" />

    @endif
@endif
