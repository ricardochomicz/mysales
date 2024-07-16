@extends('adminlte::page')

@section('title', 'Classificação')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Classificação</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('classifications.create')}}" class="btn btn-dark">Nova Classificação</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.classifications.table/>

    <x-modal_ativo_inativo titulo="Classificação"/>

@stop
