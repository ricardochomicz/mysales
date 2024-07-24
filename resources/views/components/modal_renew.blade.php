@props([
    'titulo'        => 'Registro',
    'tipo'          => '',
    'msg_ativar'    => '',
    'msg_inativar'  => '',
    'msg_encerrar'  => '',
])
<div>
    <div class="modal fade" id="renewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">  <span id="labelTitulo">Renovar</span> {{ $titulo }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center" style="padding-bottom: 0px">
                    Deseja renovar o cliente?
                    <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                    <a id="btnAtivar" type="button" href="" class="btn btn-primary">Sim, <span
                            class="labelCorpo">ativar</span></a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function ativaDesativa(rota, label, registro) {
            $('#msg_inativar').hide();
            $('#msg_ativar').hide();

            label = label.toLowerCase();
            $('#labelTitulo').html(label[0].toUpperCase() + label.substr(1));
            $('.labelCorpo').html(label);
            $('#tituloRegistro').html(registro);
            let link = document.getElementById("btnAtivar");
            link.setAttribute('href', rota);
            $('#renewModal').modal('show');
        }
    </script>
@endpush
