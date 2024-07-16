@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Pedidos</h4></div>
        <div class="p-1 bd-highlight">

        </div>
    </div>
@stop

@section('content')

    <livewire:pages.orders.table>

        <x-modal_ativo_inativo titulo="Oportunidade"/>

        <x-modal_gain titulo="Negócio Fechado" />

@stop
