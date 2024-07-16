

@extends('adminlte::page')

@section('title', "Cadastrar Fator Comissão")

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Cadastrar Fator Comissão</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('factors.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Fator Comissão</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('factors.store')}}" method="post">
        @csrf
        @include('pages.factors._partials.form')
    </form>
@stop
