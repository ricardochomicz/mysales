@extends('adminlte::page')

@section('title', 'Planos')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h4>Módulos do Plano <b>{{$plan->name}}</b></h4></div>
        <div class="p-1 bd-highlight">
            <a href="{{route('plans.index')}}" class="btn btn-secondary"><i class="fas fa-angle-left mr-1"></i> Voltar</a>
        </div>
        <div class="p-1 bd-highlight">
            <a href="{{route('plans.modules.available', $plan->id)}}" class="btn btn-dark">Adicionar Módulo</a>
        </div>

    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <livewire:adm.plans.modules.table :plan="$plan" :modules="$modules"/>
        </div>
    </div>

@stop
