@extends('adminlte::page')

@section('title', "Editar Protocolo")

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Editar Protocolo</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('protocols.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Protocolos</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('protocols.update', $data->id)}}" method="post" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        @include('pages.protocols._partials.form')
    </form>
@stop
