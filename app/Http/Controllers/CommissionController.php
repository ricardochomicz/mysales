<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\Opportunity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $view = [];
        return view('pages.commissions.index', $view);
//        $search = $request->all('search', 'date', 'operator');
//        $items = [];
//        $groupBy = $request->input('detail', 'groupBy');
//        $now = Carbon::now();
//        $query = Opportunity::with(['client', 'operadora', 'items_opportunity.product', 'items_opportunity.type', 'order_type.factor'])
//            ->where(['tenant_id' => auth()->user()->tenant->id, 'send_order' => 1, 'renew' => 0])
//            ->whereIn('status_id', [3, 4])
//            ->whereIn('type', ['order', 'wallet'])
////            ->whereMonth('activate', $now->month)
////            ->whereYear('activate', $now->year)
//            ->whereNull('deleted_at')
//            ->filterCommission($search);
//
//        $commissions = $query->get()
//            ->map(function ($commission) use (&$items, $groupBy) {
//
//                $totalAmount = 0;
//
//                if ($groupBy === 'detail') {
//                    $commissionItems = $commission->items_opportunity->flatMap(function ($item) use (&$totalAmount) {
//                        $qty = $item->qty;
//                        $factor = $item->factor;
//                        $price = $item->price;
//
//                        $amount = $qty * $factor * $price;
//                        $totalAmount += $amount;
//
////                        return [
////                            'id' => $item->id,
////                            'client' => $item->client->name,
////                            'product' => $item->product->name,
////                            'type' => $item->type->name,
////                            'operator' => $item->operator->name,
////                            'qty' => 1,
////                            'price' => $price,
////                            'factor' => $factor,
////                            'number' => $item->number,
////                            'amount' => $amount,
////                        ];
//
//                    })->toArray();
//                } else {
//                    $commissionItems = $commission->items_opportunity->groupBy('product_id')
//                        ->map(function ($items) use (&$totalAmount, $commission) {
//                            $groupedItems = [];
//                            $totalQty = 0;
//
//                            foreach ($items as $item) {
//
//                                $client = $commission->client;
//                                $operator = $commission->operator;
//                                $qty = $item->qty;
//                                $totalQty += $qty;
//                            }
//
//                            $firstItem = $items->first();
//
//                            $groupedItems[] = [
//                                'product_id' => $firstItem->product_id,
//                                'opportunity_id' => $firstItem->opportunity_id,
//                                'client' => $client->name,
//                                'product' => $firstItem->product->name,
//                                'operator' => $operator->name,
//                                'type' => $firstItem->type->name,
//                                'qty' => $totalQty,
//                                'price' => $firstItem->price,
//                                'factor' => $firstItem->factor,
//                                'amount' => $totalQty * $firstItem->factor * $firstItem->price,
//                            ];
//
//                            $totalAmount += $groupedItems[0]['amount'];
//
//                            return $groupedItems;
//                        })->toArray();
//                }
//
//                $items = array_merge($items, $commissionItems);
//
//                return [
//                    'id' => $commission->id,
//                    'client' => $commission->client,
//                    'operator' => $commission->operator,
//                    'items_opportunity' => $commission->items_opportunity,
//                    'order_type' => $commission->order_type,
//                    'qty' => $commission->lines,
//                    'factor' => $commission->order_type->factor,
//                    'total_amount' => $totalAmount
//                ];
//            });
//        $totalAmount = $commissions->sum('total_amount');
//
//        //dd($commissions);
//
//        $view = [
//            'commissions' => $commissions,
//            'items' => $items,
//            'operators' => Operator::whereNull('deleted_at')->get(),
//            'filters' => $request->only('search', 'date', 'operator'),
//            'groupBy' => $groupBy,
//            'totalAmount' => $totalAmount
//        ];
    }
}
