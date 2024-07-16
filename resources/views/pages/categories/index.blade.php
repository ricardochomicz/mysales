@extends('adminlte::page')

@section('title', 'Categorias')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Categorias</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('categories.create')}}" class="btn btn-dark">Nova Categoria</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.categories.table/>

    <x-modal_ativo_inativo titulo="Categoria"/>

@stop
