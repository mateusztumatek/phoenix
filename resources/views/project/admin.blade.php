@extends('layouts.static_page')

@section('content')
    <div class="container give-me-space">
        <h2 class="header1">Dodaj produkt do kreatora</h2>
        <div class="row justify-content-center mb-3">
            @foreach($items as $item)
                <md-card  md-with-hover >
                    <img class="w-100" src="{{url('/creator_items/'.$item->project_photo)}}" >
                    <md-card-actions>
                        <md-button @click="selectItem({{json_encode($item)}})" class="md-raised md-primary">Edytuj</md-button>
                        <md-button @click="deleteItem({{json_encode($item)}})" class="md-raised md-error">Usuń</md-button>
                    </md-card-actions>
                </md-card>
                @endforeach
                <md-card v-on:click="selectedItem({})"  md-with-hover class="d-flex flex-wrap vertical-align justify-content-center">
                    <h4>Dodaj nowy</h4>
                    <div class="w-100 text-center" >
                        <i class="fa fa-plus" style="font-size: 4rem; opacity: 0.1 "></i>
                    </div>
                    <md-card-actions>
                        <md-button @click="selectItem({})" class="md-raised md-primary">Dodaj</md-button>
                    </md-card-actions>
                </md-card>
        </div>
        <create-item v-if="selectedItem != null" :product="selectedItem" :products="{{json_encode($products)}}"></create-item>
    </div>



    @endsection
