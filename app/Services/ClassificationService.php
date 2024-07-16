<?php

namespace App\Services;

use App\Models\Classification;

class ClassificationService extends BaseService
{

    public function __construct()
    {
        parent::__construct(Classification::class);
    }

    protected function beforeDestroy($model): void
    {
        if ($model->deleted_at != null) {
            $model->restore();
        } else {
            $model->delete();
        }
    }
}
