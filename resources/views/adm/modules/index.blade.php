@extends('adminlte::page')

@section('title', 'Módulos')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Módulos</h4></div>
        <div class="p-1 bd-highlight">
            <a href="{{route('modules.create')}}" class="btn btn-dark">Novo Módulo</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <livewire:adm.modules.table/>
        </div>
    </div>

    <x-modal_ativo_inativo titulo="Módulo"/>

@stop


