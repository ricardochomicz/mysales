<?php

namespace App\Services;

use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class PlanService
{
    public function toSelect($data = [])
    {
        return Plan::orderBy('name')->get(['id', 'name']);
    }

    public function index($data)
    {
        $plans = Plan::filter($data);
        return $plans->paginate();
    }


    /**
     * @throws \Throwable
     */
    public function store(array $data): Plan
    {
        try {
            DB::beginTransaction();
            $plan = new Plan($data);
            $plan->save();

            DB::commit();
            return $plan;
        }catch (\Throwable $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function get($id, $withTrashed = false)
    {
       return $withTrashed ?
            Plan::with('modules')->withTrashed()->find($id) :
            Plan::with('modules')->find($id);
    }

    /**
     * @throws \Throwable
     */
    public function update($data, $id): Plan
    {
        try {
            DB::beginTransaction();
            $plan = $this->get($id);
            $plan->update($data);

            DB::commit();
            return $plan;
        }catch (\Throwable $e){
            dd($e);
            DB::rollBack();
            throw $e;
        }
    }
}
