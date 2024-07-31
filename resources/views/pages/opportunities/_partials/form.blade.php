<div class="card border-left-primary">
    @if( Route::currentRouteNamed('opportunities.edit'))
        <div class="ribbon-wrapper ribbon-xl">
            @if(@$data->funnel == 'correction')
                <div class="ribbon bg-danger text-lg">PARA CORREÇÃO</div>
            @endif
        </div>
    @endif


    <div class="card-body">
        @if( Route::currentRouteNamed('opportunities.create'))
            <div class="row">
                <div class="form-group col-sm-10">
                    <input class="form-control form-control-navbar typeahead2 @error('client_id') is-invalid @enderror"
                           type="search" name="query"
                           data-provide="typeahead"
                           placeholder="Pesquise pelo nome ou CNPJ">

                    <x-input type="hidden" id="client_id" name="client_id"/>
                </div>
                <div class="form-group col-sm-2">
                    <a href="{{route('clients.create')}}" class="btn btn-dark">Cadastrar Cliente</a>
                </div>
            </div>
            <div class="alert alert-danger d-none" id="empty-message">Cliente não possui contatos cadastrados. Para
                continuar cadastre <a
                    href="javascript:void(0)" class="alert-link" id="register-link">aqui</a> um novo contato.
            </div>
        @else
            <p class="h5 font-weight-bold">{{@$data->client->document}} - {{@$data->client->name}}<br><small>#{{@$data->identify}}</small></p>
            @if(@$data->client->persons[0])
                <p class="text-primary"><i class="fas fa-user-circle mr-2"></i>
                    {{@$data->client->persons[0]->name}} - <a
                        href="https://wa.me/55{{@$data->client->persons[0]->phone}}"
                        target="_blank">{{@$data->client->persons[0]->phone}}</a>
                </p>
                <a href="{{route('opportunity-proposal', @$data->uuid)}}" target="_blank" class="btn btn-primary"><i
                        class="fas fa-print"></i> Imprimir Proposta</a>
            @else
                <p class="text-danger">Nenhum contato cadastrado para o cliente. <a
                        href="{{route('clients.show', @$data->client->id)}}">Cadastre aqui.</a></p>
            @endif
        @endif
        <div class="row">
            <div class="form-group col-sm-6 mt-3 mb-3 text-danger">
                <label for="closing_probability">Probabilidade de Fechamento:</label>
                <input type="range" id="closing_probability" name="probability" min="0" max="100" step="25"
                       value="{{old(0) ?? @$data->probability}}">
                <span id="range_value">0</span>%
            </div>
            <div class="form-group col-sm-6 mt-2 mb-3 text-right">

            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-3">
                <x-select :options="$operators" id="operator_id" name="operator" title="Selecione"
                          label="Operadora" value="{{old('operator') ?? @$data->operator}}"/>
            </div>
            <div class="form-group col-sm-3">
                <x-select :options="$order_types" id="order_type_id" name="order_type" title="Selecione"
                          label="Solicitação" value="{{old('order_type') ?? @$data->order_type}}"/>
            </div>
            @if( Route::currentRouteNamed('opportunities.edit'))
                <div class="form-group col-sm-3">
                    <x-select label="Funil" :options="$funnel" id="funnel" name="funnel"
                              value="{{old('funnel') ?? @$data->funnel}}"/>
                </div>
            @endif
            <div class="form-group col-sm-3">
                <x-input name="forecast" label="Previsão" type="date"
                         value="{{old('forecast') ?? @$data->forecast}}"/>
            </div>

        </div>
        <div class="row">
            <div class="form-group col-sm-12">
                <x-text-area label="Observação" name="content"></x-text-area>
            </div>
{{--            <div class="form-group col-sm-6">--}}
{{--                <div class="card-comments"--}}
{{--                     style="margin: 0 auto; max-height: calc(30vh - 30px); overflow-y: auto;">--}}
{{--                    @if(isset($comments))--}}
{{--                        @forelse($comments as $comment)--}}
{{--                            <div class="card-comment">--}}
{{--                                <div class="comment-text">--}}
{{--                                    <span class="username">{{$comment->user->name}}--}}
{{--                                        <span--}}
{{--                                            class="text-muted float-right">{{Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:i')}}</span>--}}
{{--                                    </span>--}}
{{--                                    @if($comment->type == 'order')--}}
{{--                                        <small class="font-italic">(Pedido)</small> {{$comment->content}}--}}
{{--                                    @else--}}
{{--                                        {{$comment->content}}--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @empty--}}
{{--                            <span>Nenhum comentário!!!</span>--}}
{{--                        @endforelse--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>


        <livewire:pages.opportunities.items.product-modal/>

        <button type="submit" class="btn btn-primary" id="opportunitySubmit">Salvar</button>
        <a href="{{ route('opportunities.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>


@pushonce('scripts')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <script>

        const rangeInput = document.getElementById('closing_probability');
        const rangeValue = document.getElementById('range_value');
        rangeValue.textContent = rangeInput.value; // Definir o valor inicial

        rangeInput.addEventListener('input', (event) => {
            rangeValue.textContent = event.target.value;
        });

        $(function () {

            let route = "{{ route('clients.autocomplete') }}";
            $('.typeahead2').typeahead({
                display: 'value',
                highlight: true,
                minLength: 3,
                source: function (query, process) {
                    console.log(process)
                    return $.get(route, {
                        query: query
                    }, function (data) {
                        console.log(data)
                        process($.map(data, function (item) {

                            // return item;
                            return {
                                id: item.id,
                                name: item.document + ' - ' + item.name,
                                toString: function () {
                                    return JSON.stringify(this);
                                },
                                toLowerCase: function () {
                                    return this.name.toLowerCase();
                                },
                                indexOf: function (string) {
                                    return String.prototype.indexOf.apply(
                                        this
                                            .name, arguments);
                                },
                                replace: function (string) {
                                    return String.prototype.replace.apply(
                                        this
                                            .name, arguments);
                                }
                            }
                        }));
                    });
                },

                afterSelect: function (item) {
                    let id = item.id
                    $("#client_id").val(id)
                    let url = '{{ route("clients.getclient", ":id") }}';
                    url = url.replace(':id', item.id);
                    $.ajax({
                        type: 'get',
                        url: url,
                        dataType: 'JSON',
                        success: function (data) {
                            if (!data.persons || data.persons.length === 0) {
                                $('#register-link').attr('href', '{{ route("clients.show", ":id") }}'.replace(':id', item.id));
                                $('#empty-message').removeClass('d-none');
                                $('#opportunitySubmit').attr('disabled', true)
                            } else {
                                $('#empty-message').addClass('d-none');
                                $('#opportunitySubmit').attr('disabled', false)

                            }
                        }
                    })

                },
            });
        });

    </script>
@endpushonce
