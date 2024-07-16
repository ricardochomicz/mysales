@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Clientes</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('clients.create')}}" class="btn btn-dark">Novo Cliente</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.clients.table/>

    <x-modal_ativo_inativo titulo="Cliente"/>

@stop
