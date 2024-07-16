@extends('adminlte::page')

@section('title', 'Operadoras')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Operadoras</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('operators.create')}}" class="btn btn-dark">Nova Operadora</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.operators.table/>

@stop
