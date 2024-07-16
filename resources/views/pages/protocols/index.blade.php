@extends('adminlte::page')

@section('title', 'Protocolos')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Protocolos</h4></div>
        <div class="p-1 bd-highlight">
            <a href="{{route('protocols.create')}}" class="btn btn-dark">Novo Protocolo</a>
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.protocols.table/>
    <x-modal_ativo_inativo titulo="Protocolo"/>

@stop
