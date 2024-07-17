<?php

namespace App\Traits;

trait TypeTags
{
    public function typeTags(): array
    {
        $result = [];
        $obj = new \stdClass();
        $obj->id = 'comment';
        $obj->name = 'COMENTÁRIO';
        $result[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'opportunity';
        $obj->name = 'OPORTUNIDADE';
        $result[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'order';
        $obj->name = 'PEDIDO';
        $result[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'proposal';
        $obj->name = 'PROPOSTA';
        $result[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'protocol';
        $obj->name = 'PROTOCOLO';
        $result[] = $obj;
        return $result;
    }

    public function typeFunnel(): array{
        $result = [];
        $obj = new \stdClass();
        $obj->id = 'prospect';
        $obj->name = 'PROSPECT';
        $result[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'negotiation';
        $obj->name = 'NEGOCIAÇÃO';
        $result[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'closure';
        $obj->name = 'FECHAMENTO';
        $result[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'correction';
        $obj->name = 'PARA CORREÇÃO';
        $result[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'send_order';
        $obj->name = 'ENVIAR PEDIDO';
        $obj->show = false;
        $result[] = $obj;
        return $result;
    }

    public function protocolStatus(): array{
        $result = [];
        $obj = new \stdClass();
        $obj->id = 1;
        $obj->name = 'EM TRATAMENTO';
        $result[] = $obj;
        $obj = new \stdClass();
        $obj->id = 2;
        $obj->name = 'CANCELADO';
        $result[] = $obj;
        $obj = new \stdClass();
        $obj->id = 3;
        $obj->name = 'FINALIZADO';
        $result[] = $obj;
        return $result;
    }
}
