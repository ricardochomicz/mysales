@extends('adminlte::page')

@section('title', 'Equipes')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Equipes</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('teams.create')}}" class="btn btn-dark">Nova Equipe</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.teams.table/>

    <x-modal_ativo_inativo titulo="Equipe"/>

@stop
