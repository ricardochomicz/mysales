@extends('adminlte::page')

@section('title', 'Oportunidades')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Oportunidades</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('opportunities.create')}}" class="btn btn-dark">Nova Oportunidade</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.opportunities.table>

    <x-modal_ativo_inativo titulo="Oportunidade"/>

    <x-modal_gain titulo="Negócio Fechado" />

@stop
