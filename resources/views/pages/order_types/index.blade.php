@extends('adminlte::page')

@section('title', 'Tipo Pedido')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Tipo Pedido</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('order-types.create')}}" class="btn btn-dark">Novo Tipo Pedido</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.order-types.table/>

    <x-modal_ativo_inativo titulo="Tipo Pedido"/>

@stop
