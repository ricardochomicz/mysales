<?php

namespace App\Services;

use App\Models\Module;
use Illuminate\Support\Facades\DB;

class ModuleService
{
    private string $model = Module::class;

    public function index($data)
    {
        $module = Module::filter($data)->orderBy('label', 'asc');
        return $module->paginate();
    }


    /**
     * @throws \Throwable
     */
    public function store(array $data): Module
    {
        try {
            DB::beginTransaction();
            $module = new Module($data);
            $module->save();

            DB::commit();
            return $module;
        }catch (\Throwable $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function get($id, $withTrashed = false)
    {
        return $withTrashed ?
            Module::withTrashed()->find($id) :
            Module::find($id);
    }

    /**
     * @throws \Throwable
     */
    public function update($data, $id): Module
    {
        try {
            DB::beginTransaction();
            $module = $this->get($id);
            $module->update($data);

            DB::commit();
            return $module;
        }catch (\Throwable $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id): bool
    {
        try {
            DB::beginTransaction();
            $module = $this->get($id, true);
            if ($module->deleted_at != null) {
                $module->restore();
            } else {
                $module->delete();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}
