

@extends('adminlte::page')

@section('title', "Cadastrar Tipo Pedido")

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Cadastrar Tipo Pedido</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('order-types.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Tipo Pedido</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('order-types.store')}}" method="post">
        @csrf
        @include('pages.order_types._partials.form')
    </form>
@stop
