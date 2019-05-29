<div class="w-100 d-flex mb-5" style="flex-wrap: wrap">
    <div class="col-md-8 col-sm-12 row text-center">
        <button class="btn filter-toogle" type="button" data-toggle="collapse" data-target="#filters" aria-expanded="false" aria-controls="filters">
            <span class="fa fa-filter"> </span><span id="filter-toogle-text">Otwórz panel filtrów</span>
        </button>

        @if(\Illuminate\Support\Facades\Input::all())
            <a class="btn filter-toogle" href="{{url()->current()}}">Resetuj filtry</a>
        @endif
    </div>
    <div class="col-md-4 col-sm-12 d-flex align-items-center">
        <form method="get" action="{{route('search')}}" class="form-inline md-form d-flex form-sm active-pink-2 mt-2 w-100" style="margin: 0px !important; justify-content: flex-end">
            <input class="form-control form-control-sm mr-3 w-75" id="search-input" name="term" type="text" placeholder="Search" aria-label="Search" @if(\Illuminate\Support\Facades\Input::has('term')) value="{{\Illuminate\Support\Facades\Input::get('term')}}" @endif>
            <i onclick="$(this).parent().submit()" class="fas fa-search" aria-hidden="true" style="color: white"></i>
        </form>
    </div>

</div>
<div id="filters" class="collapse" style="width: 100%">
    <form action="{{url()->current()}}" method="GET" class="row justify-content-center" id="filter-form">
        <input type="hidden" id="duplicate_search" name="term">
        <div class="col-sm-11 mt-1 mb-5">
            <input  @if(\Illuminate\Support\Facades\Input::has('price_from')) data-value="{{\Illuminate\Support\Facades\Input::get('price_from')}}" value = "{{\Illuminate\Support\Facades\Input::get('price_from')}}" @else data-value="0" @endif type="hidden" name="price_from" >
            <input  @if(\Illuminate\Support\Facades\Input::has('price_to')) data-value="{{\Illuminate\Support\Facades\Input::get('price_to')}}" value="{{\Illuminate\Support\Facades\Input::get('price_to')}}" @else data-value="3000" @endif type="hidden" name="price_to" >
            <div class="col-sm-12 text-center">
                <label>Zakres ceny</label>
            </div>
            <div class="price-slider">
                <div  class="ui-slider-handle"><span id="custom-handle-from"></span>zł</div>
                <div  class="ui-slider-handle"><span id="custom-handle-to"></span>zł</div>
            </div>
        </div>

        <p class="mt-2">Wybierz materiał: </p>
        <div class="col-sm-12 row justify-content-center">
            @php
                $materials = DB::table('materials')->get();
            @endphp

            @foreach($materials as $key => $material)
                <div class="col-sm-4 row text-center ">
                    <input type="checkbox" name="materials[]" id="material-{{$key}}" value="{{$material->name}}" @if(\Illuminate\Support\Facades\Input::get('materials')) @if(in_array($material->name, \Illuminate\Support\Facades\Input::get('materials'))) checked @endif @endif>
                    <label for="material-{{$key}}">{{$material->name}}</label>
                </div>
            @endforeach
        </div>

        <div class="col-sm-12 mt-4 mb-4 row justify-content-center">

                <select name="sort_by" id="sort_by">
                    <option value="hide">Sortuj</option>
                    <option value="desc" @if(\Illuminate\Support\Facades\Input::has('sort_by')) @if(\Illuminate\Support\Facades\Input::get('sort_by') == 'desc') selected @endif @endif>Cena malejąco</option>
                    <option value="asc" @if(\Illuminate\Support\Facades\Input::has('sort_by')) @if(\Illuminate\Support\Facades\Input::get('sort_by') == 'asc') selected @endif @endif>Cena rosnąco</option>
                </select>


        </div>



        <button type="submit" class="btn my-button">filtruj</button>
    </form>

</div>

