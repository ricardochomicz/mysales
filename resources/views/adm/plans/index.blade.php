@extends('adminlte::page')

@section('title', 'Planos')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Planos</h4></div>
        <div class="p-1 bd-highlight">
            <a href="{{route('plans.create')}}" class="btn btn-dark">Novo Plano</a>
        </div>
    </div>
@stop

@section('content')

<livewire:adm.plans.table />

@stop
