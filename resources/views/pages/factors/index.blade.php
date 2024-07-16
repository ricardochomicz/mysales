@extends('adminlte::page')

@section('title', 'Fator Comissão')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Fator Comissão</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('factors.create')}}" class="btn btn-dark">Novo Fator Comissão</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.factors.table/>

    <x-modal_ativo_inativo titulo="Fator Comissão"/>

@stop
