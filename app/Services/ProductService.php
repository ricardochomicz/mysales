<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductService extends BaseService
{

    public function __construct()
    {
        parent::__construct(Product::class);
    }

    public function toSelectWithPrice($operatorId = false)
    {
        return Product::where('operator_id', $operatorId)->orderBy('name')->get(['id', 'name']);
    }

    // public function getAllProducts($data)
    // {
    //     $products = Product::where('tenant_id', auth()->user()->tenant->id)
    //         ->filter($data);

    //     return $products->paginate(2);
    // }

    protected function afterSave($model, array $data): void
    {
        if (isset($data['image'])) {
            $model->image = $this->uploadFile($data['image'], $model->id);
            $model->save();
        }
    }

    protected function handleUpdate(array $data, $model): array
    {
        if (isset($data['image'])) {
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
            $data['image'] = $this->uploadFile($data['image'], $model->id);
        }

        return $data;
    }

    protected function beforeDestroy($model): void
    {
        if ($model->deleted_at != null) {
            $model->restore();
        } else {
            $model->delete();
        }
    }

    private function uploadFile($data, $id)
    {
        return $data->store('/tenant/' . auth()->user()->tenant->id . '/product/' . $id, "public");
    }
}
