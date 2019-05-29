@if(\App\Services\Help::hasInputs())

    <div class="w-100 filters-panel p-3 d-flex">
    <h3 class="m-0 vertical-align">Filtry: </h3>
    @foreach(\Illuminate\Support\Facades\Request::all() as $key => $filter)
        @if($filter)
                @php
                    switch ($key){
                        case "price":
                            $k = 'cena';
                            break;
                        case "materials":
                            $k = 'materiały';
                            break;
                        case "tags":
                            $k = 'tagi';
                            break;

                    }
                @endphp
                @if(is_array($filter))

                    <div  class="filter vertical-align">@foreach($filter as $f){{$f}} @endforeach  <span class="fa fa-times"  onclick="window.location.href = removeParams('{{$key}}[]')" ></span></div>

                    @else
                    @if($key == 'price_to')
                        <div onclick="window.location.href = removeParams('price_from')" class="filter vertical-align">{{request('price_from')}} zł - {{request('price_to')}} <span class="fa fa-times"  onclick="window.location.href = removeParams('price_from')" ></span></div>
                    @endif
                    @if($key != 'price_to' && $key != 'price_from')
                        @if($key == 'sort_by')
                            @switch(request('sort_by'))
                                    @case ('seen')
                                    <div onclick="window.location.href = removeParams('{{$key}}')" class="filter vertical-align">najczęściej oglądane<span class="fa fa-times"  onclick="window.location.href = removeParams('{{$key}}[]')" ></span></div>
                                    @break

                                    @case ('price_top')
                                    <div onclick="window.location.href = removeParams('{{$key}}')" class="filter vertical-align">cena rosnąco<span class="fa fa-times"  onclick="window.location.href = removeParams('{{$key}}[]')" ></span></div>
                                    @break

                                    @case ('price_down')
                                    <div onclick="window.location.href = removeParams('{{$key}}')" class="filter vertical-align">cena malejąco<span class="fa fa-times"  onclick="window.location.href = removeParams('{{$key}}[]')" ></span></div>
                                    @break
                                @endswitch
                            @else
                             <div onclick="window.location.href = removeParams('{{$key}}')" class="filter vertical-align">{{$filter}} @if($key == 'price')zł @endif <span class="fa fa-times"  onclick="window.location.href = removeParams('{{$key}}[]')" ></span></div>
                        @endif
                        @endif
                @endif
            @endif
        @endforeach


</div>
@endif
<div class="w-100 white-color" style="margin: 0px 0px 15px 15px">
    Znaleziono: {{$count}} przedmiotów
</div>

