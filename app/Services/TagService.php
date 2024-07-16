<?php

namespace App\Services;

use App\Models\Tag;

class TagService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Tag::class);
    }

    public function toSelectOrderStatus()
    {
        return Tag::where(function ($query) {
            $query->where('tenant_id', auth()->user()->tenant->id)
                ->orWhereNull('tenant_id');
        })
            ->where('type', 'order')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function toSelectProtocol()
    {
        return Tag::where(function ($query) {
            $query->where('tenant_id', auth()->user()->tenant->id)
                ->orWhereNull('tenant_id');
        })
            ->where('type', 'protocol')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    protected function handleBeforeSave(array $data): array
    {
        $data['edit'] = true;

        return $data;
    }

    protected function beforeDestroy($model): void
    {
        if ($model->deleted_at != null) {
            $model->restore();
        }else{
            $model->delete();
        }
    }
}
