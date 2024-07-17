<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Gate;

class ClientService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Client::class);
    }
    protected function handleBeforeSave(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
        $data['classification_id'] = 1;

        return $data;
    }

    public function get($id, $withTrashed = false)
    {
        $user = auth()->user();
        $tenantId = $user->tenant->id;
        $userId = $user->id;
        $roles = $user->roles->pluck('id')->toArray();

        $query = Client::where('tenant_id', $tenantId);

        if (!in_array(2, $roles) && !in_array(3, $roles)) {
            $query->where('user_id', $userId);
        }

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->find($id);
    }

    protected function getQuery($filter)
    {
        if (in_array([2,3], auth()->user()->roles->pluck('id')->toArray())){
            $client = Client::with('user')
                ->where('tenant_id', auth()->user()->tenant->id)
                ->filter($filter);
        }
//        elseif (Gate::allows('isAdmin')) {
//            $client = Client::with('tenant')
//                ->where('tenant_id', auth()->user()->tenant->id)
//                ->where('id', '<>', 1)
//                ->filter($filter);
//        }
        else {
            $client = Client::with('user')
                ->orderBy('name', 'asc')
                ->where(['tenant_id' => auth()->user()->tenant->id, 'user_id' => auth()->user()->id])
                ->filter($filter);
        }

        return $client;
    }

//if (in_array(auth()->user()->profile_id, [1, 2, 3])) {
//$clients = Client::with('user')->orderBy('name', 'asc')->where('company_id', auth()->user()->company_id);
//} elseif (auth()->user()->profile_id == 4) {
//$clients = Client::with('user')->whereRelation('user', 'upper', '=', auth()->user()->id)->orderBy('name', 'asc')->where('company_id', auth()->user()->company_id);
//} else {
//    $clients = Client::with('user')->orderBy('name', 'asc')->where(['company_id' => auth()->user()->company_id, 'user_id' => auth()->user()->id]);
//}
}
