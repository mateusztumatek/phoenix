


        @php

            if (Voyager::translatable($items)) {
                $items = $items->load('translations');
            }
        @endphp

        @foreach ($items as $item)

            @php

                $originalItem = $item;
                if (Voyager::translatable($item)) {
                    $item = $item->translate($options->locale);
                }

                $isActive = null;
                $styles = null;
                $icon = null;

                // Background Color or Color
                if (isset($options->color) && $options->color == true) {
                    $styles = 'color:'.$item->color;
                }
                if (isset($options->background) && $options->background == true) {
                    $styles = 'background-color:'.$item->color;
                }
                   if(!$originalItem->children->isEmpty()) {
                        $linkAttributes =  'class="dropdown-toggle" data-toggle="dropdown"';
                        $caret = '<span class="caret"></span>';

                        if(url($item->link()) == url()->current()){
                            $listItemClass = 'dropdown active';
                        }else{
                            $listItemClass = 'dropdown';
                        }
                        }
                // Check if link is current
                if(url($item->link()) == url()->current()){
                    $isActive = 'active';
                }

                // Set Icon
                if(isset($options->icon) && $options->icon == true){
                    $icon = '<i class="' . $item->icon_class . '"></i>';
                }

            @endphp

            <li class="nav-item active {{isset($listItemClass) ? $listItemClass : ''}}">
                <a style="cursor: pointer" @if($item->route == 'items')id="produkty-menu" data-toggle="dropdown" @endif  @if($item->url || $item->link() && $item->route != 'items') href="{{$item->link()}}" @endif target="{{ $item->target }}" class="nav-link @if($item->title == 'produkty') dropdown-toogle @endif"  {!! isset($linkAttributes) ? $linkAttributes : '' !!}>
                    {!! $icon !!}
                    <span>{{ $item->title }}</span>
                </a>
                @if(!$originalItem->children->isEmpty())
                    @include('mymenu', ['items' => $originalItem->children, 'options' => $options])
                @endif
                @if($item->route == 'items')
                <ul role="menu" class="dropdown-menu" aria-labelledby="produkty-menu">
                    <div class="m-auto dropdown-content row ">
                        <div class="col-md-8 row align-content-center">
                            <div class="col-sm-4" >
                                <li onclick="window.location.href = $(this).children()[0].href"><a class="black-color" href="{{url('/produkty')}}">Wszystkie produkty</a></li>

                            </div>
                            @php
                                $categories = \App\Category::all();
                            @endphp
                            @foreach($categories as $category)
                                <div class="col-sm-4 col-xs-12">
                                    <li onclick="window.location.href = $(this).children()[0].href"><a class="black-color" href="{{route('category', ['category' => $category->search])}}">{{$category->name}}</a></li>
                                </div>

                            @endforeach
                            <div class="col-sm-4 col-xs-12">
                                <li onclick="window.location.href = $(this).children()[0].href"><a href="{{url('/kolekcje')}}" class="black-color">Biżuteria FANBAZOWA</a></li>

                            </div>
                        </div>
                        @php
                            $collection = \App\Collection::where('display_on_home', 1)->first();
                            if($collection){
                                                    $images = json_decode($collection->background);
                            }
                        @endphp
                        @if($collection)
                        <div class="col-sm-4 xs-hidden" style="padding: 15px 0px; display: flex">
                            <div class="menu-products row">
                                <div class="border"><div></div></div>
                                <div class="col-md-6 d-flex justify-content-around h-100" >
                                    <img src="{{url('str/'.$images[0])}}">

                                </div>
                                <div class="col-md-6 h-100 pr-4" style="padding: 15px 0px; overflow: hidden">
                                    <h2> {{$collection->name}}</h2>
                                    <p>
                                        {{$collection->intro}}
                                    </p>
                                    <a href="{{url('kolekcje?collections[]='.$collection->name)}}" style="font-size: 12px !important;" class="my-button">Zobacz więcej</a>
                                </div>
                            </div>

                        </div>
                            @endif
                    </div>

                </ul>
                @endif

            </li>
        @endforeach

       {{-- <li class="nav-item d-flex align-items-center position-relative">
            <md-badge  :md-content="cart.itemsCount">
                <md-button @click="show()" class="md-icon-button position-relative" style="overflow: visible">
                    <i class="fas fa-shopping-cart"></i>
                </md-button>
            </md-badge>
                <div class="cart-info animated fadeIn" style="animation-duration: 300ms; display:none" v-bind:style="{display: (cartMessage == null)? 'none' : 'block'}" v-if="cartMessage != null">
                    <div class="d-flex flex-wrap">
                        <div class="col-md-3 d-flex align-items-center">
                            <i style="font-size: 2rem" class="fa fa-thumbs-up w-100"></i>
                        </div>
                            <div  class="col-md-9 d-flex align-items-center">
                                <h3 class="font-weight-bold m-0" style="font-size: 1rem">@{{cartMessage}}</h3>
                            </div>
                    </div>
                </div>
        </li>--}}
        @include('saved')
