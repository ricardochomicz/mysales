<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Opportunity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function index($data)
    {
        return Opportunity::with(['ordem'])
            ->orderBy('renew_date', 'asc')
            ->where('tenant_id', auth()->user()->tenant->id)
            ->where('type', 'wallet')
            ->where('renew', 0)
            ->where('send_order',1)
            ->whereIn('status_id', [3,4])
            ->whereIn('order_type', [1,2,3])
            ->whereNull('deleted_at')
            ->filterWallets($data)
            ->paginate();
//        return Opportunity::with(['ordem'])
//            ->select(
//                'client_id',
//                DB::raw('MAX(renew_date) as renew_date'),
//                DB::raw('SUM(qty) as total_qty'),
//                DB::raw('SUM(total) as total_amount'),
//                'tenant_id',
//                'type',
//                'renew',
//                'send_order',
//                'status_id',
//                'order_type',
//                'deleted_at'
//            )
//            ->orderBy('renew_date', 'asc')
//            ->where('tenant_id', auth()->user()->tenant->id)
//            ->where('type', 'wallet')
//            ->where('renew', 0)
//            ->where('send_order', 1)
//            ->whereIn('status_id', [3, 4])
//            ->whereIn('order_type', [1, 2, 3])
//            ->whereNull('deleted_at')
//            ->groupBy(
//                'client_id',
//                'tenant_id',
//                'type',
//                'renew',
//                'send_order',
//                'status_id',
//                'order_type',
//                'deleted_at'
//            )
//            ->filterWallets($data)
//            ->paginate();
    }

    public function cloneWallet($id)
    {
        $opportunity = Opportunity::where('tenant_id', auth()->user()->tenant->id)->find($id);

//        dd($opportunity->items_opportunity);

        $opportunity->update([
            'renew' => 1,
        ]);

        $clone = $opportunity->replicate();
        $clone->send_order = 0;
        $clone->renew = 0;
        $clone->renew_date = null;
        $clone->funnel = 'closure';
        $clone->activate = null;
        $clone->type = 'opportunity';
        $clone->status_id = null;
        $clone->order_type = 3; //renovação
        $clone->forecast = Carbon::now()->format('Y-m-d');
        $clone->identify = Carbon::now()->format('YmdHis');
        $clone->save();

        $originalItems = $opportunity->items_opportunity;

        $this->comments('Oportunidade criada.', $clone->id, $clone->client->id, 'opportunity');

        // Crie cópias dos itens e associe-os à nova carteira
        foreach ($originalItems as $originalItem) {
            $clonedItem = $originalItem->replicate();
            $clonedItem->opportunity_id = $clone->id;
            $clonedItem->save();
        }

        return $clone;
    }

    private function comments($data, $opportunity, $client, $type = false): void
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
