<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Person extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'persons';

    protected $fillable = [
        'name',
        'document',
        'birthday',
        'phone',
        'email',
        'office',
        'client_id',
        'order'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn($value) => preg_replace('/\D/', '', $value)
        );
    }


}
