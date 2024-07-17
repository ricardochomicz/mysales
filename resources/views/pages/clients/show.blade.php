@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <div class="d-flex bd-highlight">
        <div class="mr-auto p-1 bd-highlight"><h3>Clientes</h3></div>
        <div class="p-1 bd-highlight">
            <a href="{{route('clients.create')}}" class="btn btn-dark">Novo Cliente</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-xl-12 mx-auto">

            <div class="invoice p-3 mb-3">
                <div class="row">
                    <div class="col-12">
                        <h5>
                            <i class="fas fa-store mr-2"></i> {{ $client->name }}
                            <small class="float-right">
                                Data Cadastro: {{ $client->created_at }}<br/>{{ $client->user->name }}
                            </small>
                        </h5>
                    </div>
                </div>

                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        <address>
                            <strong>CPF/CNPJ: {{ $client->document }}</strong><br/>
                            {{ $client->address }}, {{ $client->number }}<br/>
                            {{$client->zip_code}} - {{ $client->village }}<br/>
                            {{ $client->city }} - {{ $client->state }}<br/>
                            {{ $client->phone }}
                        </address>
                    </div>

                    <div class="col-sm-8 invoice-col">
                        <address>
                            Operadora Atual: <strong>{{ $client->operator?->name }}</strong><br/>
                            Classificação:
                            <strong>{{ $client->classification?->name }} {{$client->classification?->months}}
                            </strong><br/>
                            Número Cliente: <strong>{{ $client->number_client }}</strong><br/>
                            Senha Cliente: <strong>{{ $client->password_client }}</strong><br/>
                        </address>
                        <a href="{{route('clients.edit', $client->id)}}"
                           class="btn btn-primary btn-sm tooltips float-right" data-text="Editar">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </div>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="person" data-toggle="tab" data-target="#person-tab-pane"
                                type="button" role="tab" aria-controls="person-tab-pane" aria-selected="false">Pessoas
                            Cliente
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="order" data-toggle="tab" data-target="#order-tab-pane"
                                type="button" role="tab" aria-controls="order-tab-pane" aria-selected="true">Pedidos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="protocol" data-toggle="tab" data-target="#protocol-tab-pane"
                                type="button" role="tab" aria-controls="protocol-tab-pane" aria-selected="false">
                            Protocolos
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="person-tab-pane" role="tabpanel"
                         aria-labelledby="person-tab" tabindex="0">
                        <div class="col-12 table-responsive">

                            <livewire:pages.clients.persons.form :client="$client->id"/>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="order-tab-pane" role="tabpanel" aria-labelledby="order-tab"
                         tabindex="0">
                        <div class="col-12 table-responsive">
                            <h5 class="mt-3">Pedidos</h5>

                            <livewire:pages.clients.show.opportunities :client="$client->id" />

                        </div>
                    </div>

                    <div class="tab-pane fade" id="protocol-tab-pane" role="tabpanel" aria-labelledby="protocol-tab"
                         tabindex="0">
                        <div class="col-12 table-responsive">
                            <h5 class="mt-3">Protocolos</h5>

                            <livewire:pages.clients.show.protocols :client="$client->id" />

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <livewire:pages.clients.show.detail-opportunity />

@stop

@push('scripts')

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('closeModal', ({modalId}) => {
                const modalElement = document.querySelector(modalId);
                if (modalElement) {
                    $(modalElement).modal('hide');
                }
            });
            const modalElement = document.querySelector('#personForm');
            if (modalElement) {
                $(modalElement).on('hidden.bs.modal', function () {
                    Livewire.dispatch('resetModal');
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            let triggerTabList = [].slice.call(document.querySelectorAll('#myTab button'))
            triggerTabList.forEach(function (triggerEl) {
                let tabTrigger = new bootstrap.Tab(triggerEl)

                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault()
                    tabTrigger.show()
                    localStorage.setItem('activeTab', triggerEl.id)
                })
            })

            let activeTab = localStorage.getItem('activeTab')
            if (activeTab) {
                let someTabTriggerEl = document.getElementById(activeTab)
                let tab = new bootstrap.Tab(someTabTriggerEl)
                tab.show()
            }
        });

        // Limpar o localStorage quando a rota muda
        window.addEventListener('popstate', function (event) {
            localStorage.removeItem('activeTab');
        });

        // Adicionar listener Livewire para preservar a aba ativa durante a navegação
        document.addEventListener("livewire:load", function(event) {
            let activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                let someTabTriggerEl = document.getElementById(activeTab);
                let tab = new bootstrap.Tab(someTabTriggerEl);
                tab.show();
            }
        });





    </script>
@endpush



