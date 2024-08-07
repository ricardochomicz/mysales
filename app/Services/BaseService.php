<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BaseService
{
    protected string $modelClass;
    protected string $viewPath;

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function toSelect()
    {
        return $this->modelClass::where('tenant_id', auth()->user()->tenant->id)->orderBy('name')->get(['id', 'name']);
    }

    public function index($filter, $orderBy = [], $orderDirection = 'asc')
    {
        $query = $this->getQuery($filter);
        if (!empty($orderBy)) {
            foreach ($orderBy as $index => $field) {
                $query->orderBy($field, $orderDirection);
            }
        }
        return $query->paginate();
    }

    /**
     * @throws \Throwable
     */
    public function store(array $data)
    {

        try {
            DB::beginTransaction();
            $data = $this->handleBeforeSave($data);

            $data['tenant_id'] = auth()->user()->tenant->id;
            $model = new $this->modelClass($data);
            $model->save();

            $this->afterSave($model, $data);

            DB::commit();
            return $model;
        } catch (\Throwable $e) {
            dd($e);
            DB::rollBack();
            throw $e;
        }
    }

    public function get($id, $withTrashed = false)
    {
        return $withTrashed ?
            $this->modelClass::where('tenant_id', auth()->user()->tenant->id)->withTrashed()->find($id) :
            $this->modelClass::where('tenant_id', auth()->user()->tenant->id)->find($id);
    }

    /**
     * @throws \Throwable
     */
    public function update($data, $id)
    {
        try {
            DB::beginTransaction();

            $model = $this->get($id);
            $data = $this->handleUpdate($data, $model);

            $model->update($data);
            $this->afterUpdate($model, $data);

            DB::commit();
            return $model;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy($id): bool
    {
        try {
            DB::beginTransaction();
            $model = $this->get($id, true);
            $this->beforeDestroy($model);
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    /**
     * Método protegido para manipular dados antes de salvar.
     * Pode ser sobrescrito nas subclasses para adicionar lógica específica.
     */
    protected function handleBeforeSave(array $data): array
    {
        return $data;
    }

    /**
     * Método protegido para manipular dados depois de salvar.
     * Pode ser sobrescrito nas subclasses para adicionar lógica específica.
     */
    protected function afterSave($model, array $data): void
    {
        // Implementação padrão (vazia), pode ser sobrescrita
    }

    /**
     * Método protegido para manipular dados antes do update.
     * Pode ser sobrescrito nas subclasses para adicionar lógica específica.
     */
    protected function handleUpdate(array $data, $model): array
    {
        // Implementação padrão (vazia), pode ser sobrescrita
        return $data;
    }

    /**
     * Método protegido para manipular dados depois do update.
     * Pode ser sobrescrito nas subclasses para adicionar lógica específica.
     */
    protected function afterUpdate($model, array $data): void
    {
        // Implementação padrão (vazia), pode ser sobrescrita
    }

    protected function getQuery($filter)
    {
        // Implementação padrão, pode ser sobrescrita nas subclasses
        return $this->modelClass::where(function ($query) {
            $tenantId = auth()->user()->tenant ? auth()->user()->tenant->id : null;
            $query->where('tenant_id', $tenantId)->orWhereNull('tenant_id');
        })->filter($filter);
    }

    protected function beforeDestroy($model): void
    {
        // Implementação padrão (vazia), pode ser sobrescrita
    }
}
