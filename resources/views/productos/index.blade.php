@extends('layouts.app')

@section('title', 'Productos')

@section('content')

    <div id="app">
        <producto-index-component></producto-index-component>
    </div>
    
@stop

@section('css')
@stop

@section('js')
    <script src="{{asset('js/app.js')}}"></script>
@stop



