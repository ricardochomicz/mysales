

@extends('adminlte::page')

@section('title', "Editar Pedido")

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h4>Editar Pedido</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('orders.index')}}">Home</a></li>
                <li class="breadcrumb-item active">Pedidos</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('orders.update', $data->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('pages.orders._partials.form')
    </form>
@stop

