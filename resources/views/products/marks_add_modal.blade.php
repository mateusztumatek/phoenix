
<div class="modal fade marks_modal" id="marks_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><a class="my-link" href="{{route('index.product', ['id'=>$product->macma_id, 'product'=>\App\Services\Help::slugify($product->name)])}}">Dodaj znakowanie do produktu {{$product->name}}</a></h3>
            </div>
            <div class="modal-body">
                @foreach($marks as $mark)
                    <div data-mark_id = "{{$mark->macma_id}}" onclick="chose_mark(this)" class="col-sm-12 text-center p-3 single-mark">
                        <strong>{{$mark->name}}</strong>
                        <p> maksymalna ilość kolorów: <span style="font-weight: bold">{{$mark->colorsMax}}</span> </p>
                        <p> cena za pojedyńczy nadruk: <span style="font-weight: bold">{{number_format($mark->price_max, 2)}}zł</span></p>
                        <p> cena wszystkich nadruków: <span style="font-weight: bold">{{number_format($product->quanity * $mark->price_max, 2)}}zł</span></p>
                    </div>
                @endforeach
            </div>
            <div id="add_file" class="col-sm-12 position-relative p-3 text-center hide">

            <form method="POST" action="{{route('add_mark', ['item' => $key])}}" enctype="multipart/form-data">

                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" id="mark_id_input" name="mark_id" required>
                    <button class="file_btn" style="position: relative">Dodaj projekt
                        <input onchange="change_image(this)" id="image-input" style="width: 100%; height: 100%"  type="file" class="file_input" name="file">
                    </button>
                <button style="display:none;" type="submit" class="btn my-button">Dodaj znakowanie</button>
            </form>
            </div>

        </div>
    </div>
</div>