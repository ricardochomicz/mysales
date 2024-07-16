<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Protocol extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'user_id',
        'tag_id',
        'status',
        'number',
        'operator',
        'lines',
        'description',
        'prompt',
        'expired',
        'answer',
        'closure',
    ];

    protected $casts = [
        'lines' => 'array',
        'number' => 'array'
    ];

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function operadora(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Operator::class, 'operator');
    }

    public function tag(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function scopeFilter($query, array $filters): void
    {

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('client', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
                $query->orWhere('number', 'like', '%' . $search . '%');
            })->when($filters['trashed'] ?? null, function ($query, $trashed) {
                if ($trashed === 'only') {
                    $query->onlyTrashed();
                }
            });
        });

        $query->when($filters['tag'] ?? null, function ($query, $type) {
            $query->where('tag_id', $type);
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });

        $query->when(!($filters['search'] || $filters['tag'] || $filters['dt_ini'] && $filters['dt_end']), function ($query) use ($filters) {
            // Se não houver filtro de busca ou status, filtrar pelo mês atual
            $query->whereMonth('prompt', '=', Carbon::now()->month);
        });

        $query->when($filters['dt_ini'] && $filters['dt_end'], function ($query) use ($filters) {
            // Se datas de início e fim estão presentes, aplicar o filtro
            $query->whereDate('prompt', '>=', Carbon::parse($filters['dt_ini']));
            $query->whereDate('prompt', '<=', Carbon::parse($filters['dt_end']));
        });

    }
}
