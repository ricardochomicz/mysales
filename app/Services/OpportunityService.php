<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\ItemOpportunity;
use App\Models\Opportunity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OpportunityService
{

    public function index($data)
    {
        $opportunities = Opportunity::with(['ordem']);
        if (in_array([1,2,3], auth()->user()->roles->pluck('id')->toArray())) {
            $opportunities->where('tenant_id', auth()->user()->tenant->id)
                ->where('send_order',0)
                ->where('type', 'opportunity')
                ->orderBy('probability', 'desc')
                ->filter($data);
        }else{
            $opportunities->where('tenant_id', auth()->user()->tenant->id)
                ->where('user_id', auth()->user()->id)
                ->where('send_order',0)
                ->where('type', 'opportunity')
                ->orderBy('probability', 'desc')
                ->filter($data);
        }
        return $opportunities->paginate();
    }

    public function store($data)
    {
        try {
            DB::beginTransaction();
            $data['tenant_id'] = auth()->user()->tenant->id;
            $data['user_id'] = auth()->user()->id;
            $data['identify'] = Carbon::now()->format('YmdHis');

            $opportunity = new Opportunity($data);
            $opportunity->save();

            $allDynamicFields = $this->getAllItems();

            $this->items_opportunity($allDynamicFields, $opportunity->id);

            if (!empty($data['content'])) {
                $this->comments($data['content'], $opportunity->id, $opportunity->client_id, 'opportunity');
            }else{
                $this->comments('Oportunidade criada', $opportunity->id, $opportunity->client_id, 'opportunity');
            }

            session()->forget('items');
            DB::commit();
            return $opportunity;

        } catch (\Throwable $e) {
            dd($e);
            DB::rollback();
            return false;
        }
    }

    public function get($id, $withTrashed = false)
    {
        if ($withTrashed) {
            return Opportunity::with(['client', 'items_opportunity', 'ordem', 'operadora', 'user', 'status'])->where('tenant_id', auth()->user()->tenant->id)->withTrashed()->find($id);
        } else {
            return Opportunity::with(['client', 'items_opportunity', 'ordem', 'operadora', 'user', 'status'])->where('tenant_id', auth()->user()->tenant->id)->find($id);
        }
    }

    public function update($data, $id)
    {
        try {
            DB::beginTransaction();
            $opportunity = $this->get($id);

            if($data['funnel'] == 'send_order'){
                $this->opportunityGain($opportunity->id);
                $this->comments('Pedido Criado', $opportunity->id, $opportunity->client_id, 'order');
            }

            $opportunity->update($data);

            $allDynamicFields = $this->getAllItems();

            // Atualizar ou criar itens de oportunidade
            if (!empty($allDynamicFields)) {
                $this->items_opportunity($allDynamicFields, $opportunity->id);
            }

            if (isset($data['content'])) {
                $this->comments($data['content'], $opportunity->id, $opportunity->client_id, 'order');
            }

            session()->forget('items');
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            dd($e);
            DB::rollback();
            return false;
        }
    }

    private function getAllItems(): array
    {
        // Pegue todos os itens da sessão, filtrados ou não
        $allItems = session('items', []);

        // Se não houver items na sessão, pegue os items do próprio estado
        if (empty($allItems) && property_exists($this, 'items')) {
            $allItems = $this->items ?? [];
        }

        return $allItems;
    }

    public function items_opportunity($data, $opportunity): void
    {

        $item = ItemOpportunity::where('opportunity_id', $opportunity)->get();
        $dataResult = (array)$data;

        $results = array_map(function ($prod) {
            return ['id' => $prod['id'], 'product_id' => $prod['product_id'], 'qty' => $prod['qty'], 'order_type_id' => $prod['order_type_id'], 'number' => $prod['number'], 'price' => $prod['price'], 'factor' => $prod['factor']];
        }, $item->toArray());

        foreach ($data as $df) {
            if (count($data) > count($results)) {
                ItemOpportunity::where(['opportunity_id' => $opportunity])->updateOrCreate([
                    'tenant_id' => auth()->user()->tenant->id,
                    'opportunity_id' => $opportunity,
                    'product_id' => $df['product_id'],
                    'order_type_id' => $df['order_type_id'],
                    'number' => $df['number'],
                    'qty' => $df['qty'],
                    'price' => $df['price'],
                    'factor' => $df['factor']
                ]);

            } else {
                ItemOpportunity::where(['id' => $df['id'], 'opportunity_id' => $opportunity])->update([
                    'tenant_id' => auth()->user()->tenant->id,
                    'opportunity_id' => $opportunity,
                    'product_id' => $df['product_id'],
                    'order_type_id' => $df['order_type_id'],
                    'number' => $df['number'],
                    'qty' => $df['qty'],
                    'price' => $df['price'],
                    'factor' => $df['factor']
                ]);
            }
        }


        $idResults = array_column($results, 'id');
        $idRes = array_column($dataResult, 'id');
        $id = array_diff($idResults, $idRes);
        if (count($data) < count($results)) {
            ItemOpportunity::where(['id' => $id])->delete();
        }
    }




//    public function items_opportunity($data, $opportunity): void
//    {
//        // Pegue os itens existentes da oportunidade
//        $existingItems = ItemOpportunity::where('opportunity_id', $opportunity)->get()->keyBy('id')->toArray();
//
//        foreach ($data as $item) {
//            if (isset($item['id'])) {
//            // Verifique se o item existe e se foi modificado
//            if (isset($existingItems[$item['id']])) {
//                $existingItem = $existingItems[$item['id']];
//                // Verifique se o item foi modificado
//                if (
//                    $existingItem['product_id'] != $item['product_id'] ||
//                    $existingItem['order_type_id'] != $item['order_type_id'] ||
//                    $existingItem['number'] != $item['number'] ||
//                    $existingItem['qty'] != $item['qty'] ||
//                    $existingItem['price'] != $item['price'] ||
//                    $existingItem['factor'] != $item['factor']
//                ) {
//                    // Atualize o item se ele foi modificado
//                    ItemOpportunity::where('id', $item['id'])->update([
//                        'tenant_id' => auth()->user()->tenant->id,
//                        'product_id' => $item['product_id'],
//                        'order_type_id' => $item['order_type_id'],
//                        'number' => $item['number'],
//                        'qty' => $item['qty'],
//                        'price' => $item['price'],
//                        'factor' => $item['factor']
//                    ]);
//                }
//            }
//            } else {
//                // Crie o item se ele não existe
//                ItemOpportunity::create([
//                    'tenant_id' => auth()->user()->tenant->id,
//                    'opportunity_id' => $opportunity,
//                    'product_id' => $item['product_id'],
//                    'order_type_id' => $item['order_type_id'],
//                    'number' => $item['number'],
//                    'qty' => $item['qty'],
//                    'price' => $item['price'],
//                    'factor' => $item['factor']
//                ]);
//            }
//        }
//
//        // Delete os itens que não estão nos novos dados
//        $newItemIds = array_column($data, 'id');
//        ItemOpportunity::where('opportunity_id', $opportunity)->whereNotIn('id', $newItemIds)->delete();
//    }





    public function destroy($id): bool
    {
        try {
            DB::beginTransaction();
            $opportunity = $this->get($id, true);
            if ($opportunity->deleted_at != null) {
                $opportunity->restore();
            } else {
                $opportunity->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function opportunityGain($id): bool
    {

        try {
            DB::beginTransaction();
            $opportunity = $this->get($id);

            $opportunity->update([
                'type' => 'order',
                'status_id' => 1, //enviado bko
                'forecast' => Carbon::now()->format('Y-m-d'),
                'send_order' => 1,
                'probability' => 100
            ]);

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
