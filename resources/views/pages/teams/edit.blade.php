

@extends('adminlte::page')

@section('title', "Editar Equipes")

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Editar Equipes</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('teams.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Equipess</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('teams.update', $data->id)}}" method="post">
        @csrf
        @method('PUT')
        @include('pages.teams._partials.form')
    </form>
@stop

