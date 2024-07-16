<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOpportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'opportunity_id',
        'product_id',
        'order_type_id',
        'factor',
        'number',
        'price',
        'qty'
    ];

    public function opportunity(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Opportunity::class);
    }


    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderType::class, 'order_type_id');
    }
}
