@extends ('layouts.app')

@section('content')
    <div class="container">
        <div class="give-me-space">
            <products :allproducts="{{$products}}" :input="{{json_encode(\Illuminate\Support\Facades\Input::all())}}" :tags="{{$tags}}"></products>
        </div>
    </div>
    {{--<div class="row w-100" id="content">

        @include('products.filters_panel')

        @include('products.product_grid')

    </div>--}}

    @endsection
@section('page-title')
    @if(isset($category)){{$category->name}}
    @else Produkty
    @endif
    @endsection
@section('page-description')
    @if(isset($category)){{$category->name}}
    @else Wszystkie produkty z oferty Raccmoon-craft.pl
    @endif
@endsection
