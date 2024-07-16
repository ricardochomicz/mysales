@extends('adminlte::page')

@section('title', 'Produtos')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Produtos</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('products.create')}}" class="btn btn-dark">Novo Produto</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.products.table/>

    <x-modal_ativo_inativo titulo="Produto"/>

@stop
