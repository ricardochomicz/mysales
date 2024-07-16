<div class="card border-left-primary">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-muted">
            #{{$data->identify}} - <b>{{$data->client->name}}</b><br>
            <small>Vendedor: {{$data->user->name}}</small>
        </h6>
    </div>
    <div class="card-body">

        <div class="row" id="myForm">
            <div class="col-6">

                <div class="row">
                    <div class="form-group col-sm-6">
                        <x-input value="{{@$data->operadora->name}}" label="Operadora" readonly/>
                    </div>
                    <div class="form-group col-sm-6">
                        <x-input value="{{@$data->ordem->name}}" label="Tipo" readonly/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <x-input value="{{$data->qty}}" label="Qtd" readonly/>
                    </div>
                    <div class="form-group col-sm-6">
                        <x-input value="{{number_format($data->total, 2, ',', '.')}}" label="Receita" readonly/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <x-input type="date" name="forecast" value="{{old('forecast') ?? @$data->forecast}}"
                                 label="Data Pedido" :disabled="$data->checked == 1"/>
                    </div>
                    <div class="form-group col-sm-6">
                        <x-select :options="$status" label="Status" name="status_id" id="status"
                                  value="{{old('status_id') ?? @$data->status_id}}" :disabled="$data->checked == 1"/>

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <x-input type="date" onchange="getDateMaturity()" name="activate" label="Ativação"
                                 value="{{old('activate') ?? @$data->activate}}" :disabled="$data->checked == 1"/>
                    </div>
                    @can('isAdmin')

                        <div class="form-group col-sm-6 mt-5">
                            <x-switch label="Pedido Conferido" id="checked" value="1" name="checked"
                                      onchange="toggleInputs(this)" conteudo="{{ old('checked', @$data->checked) }}"/>
                        </div>
                    @endcan
                    <x-input type="hidden" name="renew_date" id="renew_date"
                             value="{{old('renew_date') ?? @$data->renew_date}}"/>
                </div>
            </div>

            <div class="col-6">
                <p>Observações Pedido</p>
                <livewire:pages.orders.order-comments.form-comment :opportunity="$data->id"/>
            </div>
        </div>


        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>

@push('scripts')
    <script>
        function getDateMaturity() {
            let activateDate = document.querySelector("input[name='activate']").value;
            if (activateDate) {
                let data = new Date(activateDate);
                if (!isNaN(data.getTime())) {
                    data.setMonth(data.getMonth() + 18);
                    let year = data.getFullYear();
                    let month = ('0' + (data.getMonth() + 1)).slice(-2);
                    let day = ('0' + data.getDate()).slice(-2);
                    let formattedDate = `${year}-${month}-${day}`;
                    document.getElementById('renew_date').value = formattedDate;
                } else {
                    console.error("Data inválida");
                }
            }
        }

        function toggleInputs(checkbox) {
            const form = document.getElementById('myForm');
            const inputs = form.querySelectorAll('input[type="text"],input[type="date"], input[type="number"], select');
            const selects = form.querySelectorAll('select');
            inputs.forEach(input => {
                input.disabled = checkbox.checked;
            });


        }
    </script>
@endpush

@push('styles')
    <style>
        .disabled-selectpicker {
            pointer-events: none;
            opacity: 0.5;
            background-color: #f9f9f9 !important; /* Cor de fundo para indicar desabilitado */
        }
    </style>
@endpush
