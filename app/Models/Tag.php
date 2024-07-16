<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['tenant_id', 'name', 'type', 'edit'];


    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });

    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => mb_strtoupper($value),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('d/m/Y'),
        );
    }

    public function getTagTypeAttribute()
    {
        if ($this->type === 'comment') {
            return 'Comentário';
        } elseif ($this->type === 'opportunity') {
            return 'Oportunidade';
        } elseif ($this->type === 'order') {
            return 'Pedido';
        } elseif ($this->type === 'proposal') {
            return 'Proposta';
        } elseif ($this->type === 'protocol') {
            return 'Protocolo';
        }
    }

    public static function typeTags(): array
    {
        $retorno = [];
        $obj = new \stdClass();
        $obj->id = 'comment';
        $obj->name = 'Comentário';
        $obj->text = '';
        $retorno[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'opportunity';
        $obj->name = 'Oportunidade';
        $obj->text = '';
        $retorno[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'order';
        $obj->name = 'Pedido';
        $obj->text = '';
        $retorno[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'proposal';
        $obj->name = 'Proposta';
        $obj->text = '';
        $retorno[] = $obj;
        $obj = new \stdClass();
        $obj->id = 'protocol';
        $obj->name = 'Protocolo';
        $obj->text = '';
        $retorno[] = $obj;
        return $retorno;
    }

}
