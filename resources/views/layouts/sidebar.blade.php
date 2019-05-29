<form action="{{url()->current()}}" method="GET" id="filter-form">

    <div class="collapsed navbar-collapse nav-side-menu menu-content" id="side_menu">
        <div>



            <h3 class="text-left mb-5">FILTRY</h3>

            @if(\Illuminate\Support\Facades\Request::is('kolekcje'))
                <p class="mb-2"> KOLEKCJE: </p>
                @foreach($collections as $key => $collection)
                    <input class="styled-checkbox" id="styled-checkbox-collection{{$key}}" @if(request('collection')) @if(in_array("$collection->name", request('collection'))) checked @endif @endif name="collections[]" type="checkbox" value="{{$collection->name}}">
                    <label style="padding-left: 28px;" for="styled-checkbox-collection{{$key}}">{{$collection->name}}</label>
                    @endforeach
            @else
            <p class="mb-2"> CENA: </p>
            <div class="row mb-2" id="price_slider_controls" @if(request('price_from') == null) style="display: none" @endif>
                <div class="col-sm-4 col-xs-4 d-flex">
                    <input onchange="updateSlider()" type="text" class="form-control text-center" style="border: none !important;" name="price_from" id="price_from_input"><p class="vertical-align m-0" style="margin-bottom: 0px !important;">zł</p>
                </div>
                <div class="col-sm-1 d-flex vertical-align">
                    <span style="font-weight: bold">-</span>
                </div>
                <div class="col-sm-4 col-xs-2 d-flex">
                    <input type="text" onchange="updateSlider()" name="price_to" class="form-control text-center" id="price_to_input" style="border: none !important;"><p class="vertical-align mb-0" style="margin-bottom: 0px !important;">zł</p>
                </div>
            </div>
                <div id="slider-range"></div>

                <p class="mt-4 mb-2 col-xs-4">MATERIAŁY:</p>
            @php
                $materials = \App\Material::all();

            @endphp
            @foreach($materials as $key => $material)
                <input class="styled-checkbox" id="styled-checkbox-{{$key}}" @if(request('materials')) @if(in_array("$material->name", request('materials'))) checked @endif @endif name="materials[]" type="checkbox" value="{{$material->name}}">
                <label style="padding-left: 28px;" for="styled-checkbox-{{$key}}">{{$material->name}}</label>

            @endforeach
            <p class="mt-4 mb-2">Sortuj według:</p>
            <select name="sort_by" class="form-control custom-select">
                <option value="none"> Sortuj według</option>
                <option value="seen" @if(request('sort_by') == 'seen') selected @endif>Najczęściej oglądane</option>
                <option value="price_top" @if(request('sort_by') == 'price_top') selected @endif>Najwyższej ceny</option>
                <option value="price_down" @if(request('sort_by') == 'price_down') selected @endif>Najniższej ceny</option>
            </select>
            <p class="mt-4 mb-2">TAGI:</p>
            <div class="row m-0">
                <input type="hidden" id="tag_input" name="tags" @if(request('tags')) value="{{request('tags')}}" @endif>
                @foreach($tags as $key => $tag)

                    <div onclick="$('#tag_input').val('{{$tag->tag}}'); $('#filter-form').submit();" class="tag">{{$tag->tag}}</div>

                @endforeach
            </div>
            @php
                $popularity_products = \App\Product::orderBy('count', 'desc')->take(5)->get();
            @endphp
            <p class="mt-3 mb-2">Popularne produkty</p>
            <div class="d-flex">
                <div class="populary-product-slider">
                    @foreach($popularity_products as $product)
                        <div class="w-100">
                            <a style="cursor: pointer" data-product_id = "{{$product->id}}" data-slug = "{{\App\Services\Help::slugify($product->name)}}" onclick ="quick_view(this, event)"><img src="{{$product->getProductImages()[0]}}" class="w-100"></a>
                        </div>
                    @endforeach
                </div>
            </div>

                @endif
            <div class="mt-4">
                <button style="font-size: 1rem !important;" type="submit" class="btn my-button w-50">Filtruj</button>

            </div>
            @if(\App\Services\Help::hasInputs())
                <div class="mt-4">
                    <button type="button" style="font-size: 1rem !important;" onclick="window.location.href = window.location.href.substring(0, window.location.href.indexOf('?'))" class="btn my-button w-100">Usuń wszystkie filtry</button>

                </div>
            @endif
        </div>
        <button type="button" @click="show()" class="my-button">
            Otwórz projekt
        </button>


</div>
</form>

@section('scripts_after')
    <script>
        @if(request('price_from') != null)
                slider_values = [{{request('price_from')}}, {{request('price_to')}}];
        @endif
    </script>

@endsection
