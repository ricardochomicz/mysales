@extends('adminlte::page')

@section('title', 'Tags')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Tags</h4></div>
        <div class="p-1 bd-highlight">
            @can('manage-users')
                <a href="{{route('tags.create')}}" class="btn btn-dark">Nova Tag</a>
            @endcan
        </div>
    </div>
@stop

@section('content')

    <livewire:pages.tags.table/>

    <x-modal_ativo_inativo titulo="Tag"/>

@stop
