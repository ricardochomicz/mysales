<?php

namespace App\Services;

use App\Models\Factor;

class FactorService extends BaseService
{

    public function __construct()
    {
        parent::__construct(Factor::class);
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
