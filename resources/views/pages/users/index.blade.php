@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Usuários</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('users.create')}}" class="btn btn-dark">Novo Usuário</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.users.table/>

    <x-modal_ativo_inativo titulo="Usuário"/>

@stop
