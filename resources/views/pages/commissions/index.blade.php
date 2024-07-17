@extends('adminlte::page')

@section('title', 'Comissão')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Comissão</h4></div>
    </div>
@stop

@section('content')

    <livewire:pages.commissions.table/>

    <x-modal_ativo_inativo titulo="Comissão"/>

@stop
