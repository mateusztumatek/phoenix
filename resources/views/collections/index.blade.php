@extends ('layouts.app')

@section('content')
    <div class="container">
        <div class="give-me-space">
            <products :allproducts="{{json_encode($products)}}" :input="{{json_encode(\Illuminate\Support\Facades\Input::all())}}" :collections="{{json_encode($collections)}}"></products>
        </div>
    </div>
@endsection
@section('page-title')
    @if(isset($category)){{$category->name}}
    @else Produkty
    @endif
@endsection
@section('page-description')
    @if(isset($category)){{$category->name}}
    @else Wszystkie produkty z oferty Firmowygadżet.
    @endif
@endsection