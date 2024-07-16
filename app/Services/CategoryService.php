<?php

namespace App\Services;

use App\Models\Category;

class CategoryService extends BaseService
{

    public function __construct()
    {
        parent::__construct(Category::class);
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
