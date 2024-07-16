@props([
    'titulo'        => 'Registro',
    'tipo'          => '',
    'msg_ativar'    => '',
    'msg_inativar'  => '',
    'msg_encerrar'  => '',
])
<div>

    <div class="modal fade" id="modalGain" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success">  <span id="labelTitulo"></span> {{ $titulo }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding-bottom: 0px">
                    <div class="text-center">
                    Enviar para Pedidos?  <span class="labelCorpo">ativar</span>
                    <br>
                    <p><b id="tituloRegistro"></b></p>
                    </div>
                    <x-text-area name="content" label="Observação" class="mb-2"></x-text-area>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <a id="btnGain" type="button" href="" class="btn btn-primary">Sim</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function gainModal(rota, label, registro) {

            label = label.toUpperCase();
            $('#labelTitulo').html(label[0].toUpperCase() + label.substr(1));
            $('.labelCorpo').html(label);
            $('#tituloRegistro').html(registro);
            let link = document.getElementById("btnGain");
            link.setAttribute('href', rota);
            $('#modalGain').modal('show');
        }
    </script>
@endpush
