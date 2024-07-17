<div class="card border-left-primary">

    <div class="card-body">

        <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id', @$data->client_id) }}">
        @if (Route::currentRouteNamed('protocols.edit'))
            <h5 class="mt-3 mb-5">{{@$data->client->document}} - {{@$data->client->name}}</h5>
        @else
            <div class="row">
                <div class="form-group col-sm-8">
                    <input type="search" class="form-control typeahead-search @error('client_id') is-invalid @enderror"
                           name="query"
                           placeholder="Pesquise pelo Nome ou CNPJ"
                           data-provide="typeahead">
                    @error('client_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="alert alert-danger d-none" id="empty-message">Cliente não possui contatos cadastrados. Clique <a
                    href="javascript:void(0)" class="alert-link" id="register-link">aqui</a> para cadastrar.
            </div>
            <div class="row">
                <div class="col-12 mb-2">
                    <p class="text-center">
                        <i class="fas fa-sort-down" data-toggle="collapse" href="#collapseExample" role="button"
                           aria-expanded="false" aria-controls="collapseExample">
                        </i>
                    </p>
                    <div class="collapse p-1 py-2 border rounded mb-3 alert alert-primary" id="collapseExample">

                        <div class="row">
                            <div class="col-auto">
                                <span><b>Contato:</b></span> <span id="adm"></span>
                            </div>
                            <div class="col-sm-2">
                                <span><b>CPF:</b></span> <span id="cpf"></span>
                            </div>
                            <div class="col-sm-4">
                                <span><b>Senha Atendimento:</b></span> <span id="senha"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <span><b>CEP:</b></span> <span id="cep"></span>
                            </div>
                            <div class="col-auto">
                                <span><b>End.:</b></span> <span id="address"></span> <span id="number"></span>
                            </div>
                            <div class="col-sm-3">
                                <span><b>Bairro:</b></span> <span id="village"></span>
                            </div>
                            <div class="col-auto">
                                <span><b>Cidade:</b></span> <span id="city"></span> <span id="state"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif

        <div class="row">
            <div class="form-group col-sm-3">
                <x-select :options="$tags" label="Tipo Protocolo" name="tag_id" id="tag_id" title="Selecione"
                          value="{{ old('tag_id', @$data->tag_id) }}"/>
            </div>
            <div class="form-group col-sm-3">
                <x-select :options="$operators" label="Operadora" name="operator" id="operator" title="Selecione"
                          value="{{ old('operator', @$data->operator) }}"/>
            </div>

            <div class="form-group col-sm-6">
                <x-input label="Linha(s)" name="lines" data-role="tagsinput" placeholder="digite e pressione enter"
                         value="{{ old('lines', @$data->lines) }}"/>

            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-6">
                <x-text-area label="Descrição" name="description"
                             value="{{old('description') ?? @$data->description}}"></x-text-area>
            </div>
            <div class="form-group col-sm-6">
                <x-text-area label="Resposta" name="answer" value="{{old('answer') ?? @$data->answer}}"></x-text-area>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <x-input label="Número Protocolo" name="number" data-role="tagsinput" class="inputTag"
                         placeholder="digite e pressione enter" value="{{old('number') ?? @$data->number}}"/>

            </div>
            @if (Route::currentRouteNamed('protocols.edit'))
                <div class="form-group col-sm-6">
                    <label for="input-6a">Arquivo</label>
                    <div class="file-loading">
                        <input id="input-6a" name="archive" type="file" class="imagem" accept="*">
                    </div>
                </div>
            @endif
        </div>
        @if (Route::currentRouteNamed('protocols.edit'))
            <div class="row">
                <div class="form-group col-sm-3">
                    <x-select label="Status" name="status" id="status" :options="$status"
                              value="{{ old('status', @$data->status) }}"/>

                </div>
                <div id="closure" class="form-group col-sm-2">
                    <x-input type="date" name="closure" label="Finalizado em"
                             value="{{ old('closure', @$data->closure) }}"/>
                </div>
            </div>
        @endif

        @if (Route::currentRouteNamed('protocols.create'))

            <div class="mb-3 form-check">
                <input type="checkbox" name="send_email" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Enviar E-mail?</label>
            </div>

        @endif

        <button type="submit" class="btn btn-primary" id="protocolSubmit"
                onclick="this.disabled=true;this.form.submit();myFunction()">
            Salvar
        </button>
        <a href="{{ route('protocols.index') }}" class="btn btn-secondary">Voltar</a>

    </div>

</div>

@push('scripts')
    <script src="{{asset('/assets/plugins/inputfile/js/inputfile.min.js')}}"></script>
    <script src="{{asset('/assets/js/notify.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("input[type='file']").fileinput({
                showUpload: true,
                dropZoneEnabled: false,
                maxFileCount: 10,
                language: 'pt-BR',
                browseClass: 'btn btn-secondary',
                removeClass: 'btn btn-danger',
                uploadClass: 'd-none',
                //required: true,
                // allowedFileExtensions: ['webp', 'gif', 'png', 'bmp', 'jpg', 'jpeg', 'tif']
            });
        });

        $(function () {
            let route = "{{ route('clients.autocomplete') }}";
            $('.typeahead-search').typeahead({

                display: 'value',
                highlight: true,
                minLength: 3,

                source: function (query, process) {

                    return $.get(route, {
                        query: query
                    }, function (data) {

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
                                },
                            }
                        }));
                    });
                },

                afterSelect: function (item) {
                    $("#client_id").val(item.id);
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
                                $('#protocolSubmit').attr('disabled', true)
                            } else {
                                $('#empty-message').addClass('d-none');
                                $('#protocolSubmit').attr('disabled', false)
                                $('#name').html(data.name)
                                $('#address').html(data.address)
                                $('#number').html(data.number)
                                $('#village').html(data.village)
                                $('#city').html(data.city)
                                $('#state').html(data.state)
                                $('#email').html(data.email)
                                $('#cep').html(data.zip_code)
                                $('#senha').html(data.password_client)
                                $('#adm').html(data.persons[0].name)
                                $('#cpf').html(data.persons[0].document)
                            }
                        }
                    })
                },

            });
        });

    </script>
@endpush
