@extends('adminlte::page')

@section('title', 'Editar Módulo')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Editar Módulo</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('modules.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Módulos</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('modules.update', $module->id)}}" method="post">
        @csrf
        @method('PUT')
        @include('adm.modules._partials.form')
    </form>
@stop
