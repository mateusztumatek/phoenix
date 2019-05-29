
<div class="modal fade marks_modal" id="colours_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><a class="my-link" href="{{route('index.product', ['id'=>$product->macma_id, 'product'=>\App\Services\Help::slugify($product->name)])}}">Zmień kolor produktu {{$product->name}}</a></h3>
            </div>
            <div class="modal-body">
                <form id="change_colour_form" method="POST" action="{{route('change_colour', ['item' => $key])}}" enctype="multipart/form-data">

                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="colour" id="colour_input" required>
                    <input type="hidden" name="colour_hex" id="colour_hex_input">
                    @foreach($colours as $color)
                        <div onclick="change_colour(this, '{{$color->colour}}', '{{$color->colourHex}}')" style="background-color: #{{$color->colourHex}}" class="col-sm-12 text-center p-3 single-mark">
                            <strong data-colour="{{$color->colourHex}}" class="dynamic-color">{{$color->colour}}</strong>
                        </div>
                    @endforeach
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.dynamic-color').each(function (index, elem) {
           if(is_dark_color($(elem).attr('data-colour'))){
               $(elem).addClass('light-color');
            }
        });
    })
</script>
