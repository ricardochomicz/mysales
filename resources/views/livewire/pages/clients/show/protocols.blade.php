<div>
    <table class="table">
        <thead>
        <tr>
            <th>Protocolo</th>
            <th class="text-center">Operadora</th>
            <th class="text-center">Tipo Protocolo</th>
            <th class="text-center">Status</th>
            <th class="text-center">Criado em</th>
            <th class="text-center">Finalizado em</th>
            <th class="text-center">...</th>
        </tr>
        </thead>
        <tbody>

        @foreach($protocols as $protocol)
            <tr>
                <td>
                    {{$protocol->number}}

                </td>
                <td class="text-center">
                    {{$protocol->operadora->name}}
                </td>
                <td class="text-center">
                    {{$protocol->tag->name}}
                </td>
                <td class="text-center">
                    {!! $this->getNameStatus($protocol->status) !!}
                </td>
                <td class="text-center">
                    {{\Carbon\Carbon::parse($protocol->created_at)->format('d/m/Y')}}<br>
                    @if(!$protocol->closure)
                        <small class="text-danger">Expira
                            em {{Carbon\Carbon::parse($protocol->prompt)->format('d/m/Y')}}</small>
                    @endif
                </td>
                <td class="text-center">
                    @if($protocol->closure)
                        {{Carbon\Carbon::parse($protocol->closure)->format('d/m/Y')}}
                    @endif
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center pagination-sm">
        {!!  $protocols->links('vendor.pagination.bootstrap-4') !!}
    </div>
</div>

