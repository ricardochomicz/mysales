@extends('adminlte::page')

@section('title', 'Empresa')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight">
            <h4>
                Empresa
            </h4>
        </div>
        <div class="p-1 bd-highlight">
            @can('isSuperAdmin')
                <a href="{{route('tenants.create')}}" class="btn btn-dark">Nova Empresa</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.tenants.table/>

    <x-modal_ativo_inativo titulo="Empresa"/>

@stop
