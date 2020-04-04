@php
    $images = json_decode($static_banner->image);

@endphp
<div class="static-banner d-flex justify-content-start align-items-center give-me-space" >
    <img class="lazy" data-src="{{url('/str/'.$images[0])}}">

    <div class="text-left" style="z-index: 1000">
        <h3 class="banner-header">{{$static_banner->name}}</h3>
        <div class="banner-content">
            @php

                echo html_entity_decode($static_banner->content);
            @endphp
        </div>

    </div>
</div>