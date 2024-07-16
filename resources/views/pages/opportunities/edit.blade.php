@extends('adminlte::page')

@section('title', "Editar Oportunidade")

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Editar Oportunidade</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('opportunities.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Oportunidades</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('opportunities.update', $data->id)}}" method="post">
        @csrf
        @method('PUT')
        @include('pages.opportunities._partials.form')
    </form>
@stop


