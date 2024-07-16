<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TenantService
{

    public function index($data)
    {
        if(Gate::allows('isSuperAdmin', auth()->user())){
            return Tenant::filter($data)->paginate();
        }else{
            return Tenant::filter($data)->where('id', auth()->user()->tenant->id)->paginate();
        }
    }

    public function get($id, $withTrashed = false)
    {
        if(Gate::allows('isSuperAdmin', auth()->user())) {
            return $withTrashed ?
                Tenant::with('plan')->withTrashed()->find($id) :
                Tenant::with('plan')->find($id);
        }else{
            return $withTrashed ?
                Tenant::where('id', auth()->user()->tenant->id)->withTrashed()->find($id) :
                Tenant::where('id', auth()->user()->tenant->id)->find($id);
        }
    }

    public function store($data)
    {
        try {
            DB::beginTransaction();
            $tenant = new Tenant($data);

            if (isset($data['logo'])) {
                $data['logo'] = $this->uploadLogo($data['logo']);
            }

            $tenant->save();

            DB::commit();
            return $tenant;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    public function update($data, $id)
    {
        try {
            DB::beginTransaction();

            $tenant = $this->get($id);

            if (!isset($data['subscription_active'])) {
                $data['subscription_active'] = false;
            }
            if (!isset($data['subscription_suspended'])) {
                $data['subscription_suspended'] = false;
            }

            if (isset($data['logo'])) {
                if ($tenant->logo) {
                    Storage::disk('public')->delete($tenant->logo);
                }
                $data['logo'] = $this->uploadLogo($data['logo']);
            }

            $tenant->update($data);

            DB::commit();
            return $tenant;
        } catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
            return false;
        }
    }

    public function destroy($id): bool
    {
        try {
            DB::beginTransaction();
            $tenant = $this->get($id, true);
            if ($tenant->deleted_at != null) {
                $tenant->restore();
                $tenant->update(['subscription_suspended' => false]);
            } else {
                $tenant->delete();
                $tenant->update(['subscription_suspended' => true]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    private function uploadLogo($data)
    {
        return $data->store('/tenant/' . auth()->user()->tenant->id, "public");
    }


}
