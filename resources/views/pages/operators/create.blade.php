

@extends('adminlte::page')

@section('title', "Cadastrar Operadora")

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Cadastrar Operadora</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('operators.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Operadoras</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('operators.store')}}" method="post">
        @csrf
        @include('pages.operators._partials.form')
    </form>
@stop

