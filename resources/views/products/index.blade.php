@extends('layouts.app')

@section('content')

        <product :product="{{$product}}" :featured="{{json_encode($featured_products)}}"></product>
    @endsection

@section('page-title') {{$product->name}} z nadrukiem - Firmowygadżet.pl
@endsection
@section('page-description')
@endsection
