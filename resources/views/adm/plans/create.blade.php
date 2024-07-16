@extends('adminlte::page')

@section('title', 'Cadastrar Plano')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Cadastrar Plano</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('plans.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Planos</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('plans.store')}}" method="post">
        @csrf
        @include('adm.plans._partials.form')
    </form>
@stop

