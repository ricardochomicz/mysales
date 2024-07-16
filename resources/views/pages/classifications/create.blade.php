

@extends('adminlte::page')

@section('title', "Cadastrar Classificação")

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Cadastrar Classificação</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('classifications.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Classificação</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('classifications.store')}}" method="post">
        @csrf
        @include('pages.classifications._partials.form')
    </form>
@stop
