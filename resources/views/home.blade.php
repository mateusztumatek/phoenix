@extends ('layouts.app')

@section('content')
    <div class="row" id="content">
        @include('products.filters_panel')

        @include('products.product_grid')

    </div>

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
