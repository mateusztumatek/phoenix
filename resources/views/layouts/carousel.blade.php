@php
    $carousel_items = \App\Po::orderBy('created_at', 'desc')->where('type', 'appearance_carousel')->get();
@endphp

<div id="carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        @foreach($carousel_items as $key => $carousel_item)
        <div class="carousel-item @if($key == 0) active @endif">
            @php
                    $images = json_decode($carousel_item->image);

                    @endphp
            <img class="d-block w-100 lazy" data-src="{{url('/').'/str/'.$images[0]}}" alt="First slide">
            <div class="carousel-item-content d-flex">
                <div class="content" style="overflow: hidden">
                    <div class="text-left" >
                        <h2 class="fadeInLeft animated delay-200 shadow-text">{!! $carousel_item->name !!}</h2>
                        <div class="text-white-important fadeInLeft animated delay-500 shadow-text">
                            {!! $carousel_item->content !!}
                        </div>

                        @if($carousel_item->url)
                            <a class="btn my-button fadeInUp animated delay-1s white-color" href="{{url('').$carousel_item->url}}">Zobacz więcej</a>
                        @endif

                    </div>
                </div>


            </div>
        </div>
        @endforeach
    </div>

    <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>

    <div class="carousel-after">
        <div class="d-flex justify-content-center">
            <div class="slide-down"></div>

        </div>
    </div>
</div>
