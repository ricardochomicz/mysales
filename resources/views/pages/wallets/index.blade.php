@extends('adminlte::page')

@section('title', 'Carteira')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Carteira</h4></div>
        <div class="p-1 bd-highlight">

        </div>
    </div>
@stop

@section('content')

    <livewire:pages.wallets.table>

        <x-modal_ativo_inativo titulo="Pedido"/>

@stop
