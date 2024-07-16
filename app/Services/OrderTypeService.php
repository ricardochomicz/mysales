<?php

namespace App\Services;

use App\Models\OrderType;

class OrderTypeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(OrderType::class);
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
