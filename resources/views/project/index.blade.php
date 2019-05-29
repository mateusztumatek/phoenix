@extends('layouts.static_page')
@section('content')
    <div class="container give-me-space">

        <draw-component  :files="{{json_encode($files)}}" :project="{{json_encode($project)}}"></draw-component>
    </div>
@endsection