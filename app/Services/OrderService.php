<?php

namespace App\Services;

use App\Mail\OrderActivate;
use App\Models\Comment;
use App\Models\Opportunity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderService
{

    public function index($data)
    {
        return Opportunity::with(['ordem'])
            ->where('tenant_id', auth()->user()->tenant->id)
            ->where('send_order', 1)
            ->whereIn('type', ['order', 'wallet'])
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'asc')
            ->filterOrders($data)->paginate();
    }

    public function get($id)
    {
        return Opportunity::with(['client', 'items_opportunity', 'ordem', 'operadora', 'user', 'status'])
            ->where('tenant_id', auth()->user()->tenant->id)->find($id);
    }

    public function update($data, $id)
    {
        $tagService = new TagService();

        try {
            DB::beginTransaction();
            $order = $this->get($id);

            if (isset($data['checked'])) {
                $order->update([
                    'checked' => $data['checked']
                ]);
                $this->comments('Pedido Conferido', $order->id, $order->client_id, 'order');
            } else {
                $data['checked'] = 0;
                if ((int)$data['status_id'] === 7) { //para correção
                    $data['send_order'] = 0;
                    $data['type'] = 'opportunity';
                    $data['funnel'] = 'correction';
                    $data['probability'] = 50;
                }

                $tag = $tagService->getTagOrder($data['status_id']);

                $this->comments("Pedido Movimentado: $tag->name", $order->id, $order->client_id, 'order');

                if ((int)$data['status_id'] === 3 || (int)$data['status_id'] === 4) {
                    $data['type'] = 'wallet';
                    if($order->activate >= Carbon::now()){
                        $this->comments('Pedido Faturado/Ativo', $order->id, $order->client_id, 'order');
                    }
                } elseif ((int)$data['status_id'] === 7) {
                    $this->comments('Devolvido Consultor', $order->id, $order->client_id, 'order');
                }


                $order->update($data);

                if ($order->client->persons[0]->email != '' && ((int)$data['status_id'] === 3 || (int)$data['status_id'] === 4)) {
                    $order->client->update(['operator_id' => $order->operator]);
                    Mail::to(@$order->client->persons[0]->email)
                        ->cc('empresas.atendimento@gmail.com')
                        ->send(new OrderActivate($order));
                }

            }

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            dd($e);
            DB::rollback();
            return false;
        }
    }

    public function comments($data, $opportunity, $client, $type = false): void
    {
        Comment::create([
            'tenant_id' => auth()->user()->tenant->id,
            'opportunity_id' => $opportunity,
            'client_id' => $client,
            'user_id' => auth()->user()->id,
            'content' => $data,
            'type' => $type
        ]);
    }

}
