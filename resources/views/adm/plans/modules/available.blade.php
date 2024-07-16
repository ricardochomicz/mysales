@extends('adminlte::page')

@section('title', 'Adicionar Módulo')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Adicionar Módulo - Plano <b>{{$plan->name}}</b></h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('plans.modules', $plan->id)}}">Home</a></li>
                <li class="breadcrumb-item active">Módulos Plano</li>
            </ol>
        </div>
    </div>
@stop

@section('content')

    <livewire:adm.plans.modules.available :plan="$plan" :modules="$modules"/>

@stop


