<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opportunity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'user_id',
        'order_type',
        'operator',
        'forecast',
        'renew',
        'renew_date',
        'identify',
        'funnel',
        'activate',
        'type',
        'send_order',
        'activity_date',
        'activity_status',
        'total',
        'qty',
        'probability',
        'status_id',
        'checked'
    ];

    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ordem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderType::class, 'order_type');
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

        public function operadora(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Operator::class, 'operator');
    }

    public function items_opportunity(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemOpportunity::class, 'opportunity_id', 'id');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'item_opportunities')
            ->withPivot('qty', 'price');
    }

//    public function getItemsPaginationAttribute()
//    {
//        return $this->items_opportunity()->paginate(2);
//    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

//    protected function updatedAt(): Attribute
//    {
//        return Attribute::make(
//            get: fn(string $value) => \Illuminate\Support\Carbon::parse($value)->format('d/m/Y'),
//        );
//    }



    public function scopeFilter($query, array $filters): void
    {

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('client', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
                $query->orWhere('identify', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });

        $query->when($filters['funnel'] ?? null, function ($query, $status) {
            // Filtro de status independente
            if ($status === 'prospect') {
                $query->where('funnel', 'PROSPECT');
            } elseif ($status === 'negotiation') {
                $query->where('funnel', 'NEGOCIAÇÃO');
            } elseif ($status === 'closure') {
                $query->where('funnel', 'FECHAMENTO');
            } elseif ($status === 'correction') {
                $query->where('funnel', 'CORREÇÃO');
            }
        });

        $query->when($filters['probability'] ?? null, function($query, $probability){
            $query->where('probability', $probability);
        });

        $query->when($filters['type'] ?? null, function ($query, $type) {
            $query->where('order_type', $type);
        });

        $query->when($filters['dt_ini'] && $filters['dt_end'], function ($query) use ($filters) {
            // Se datas de início e fim estão presentes, aplicar o filtro
            $query->whereDate('forecast', '>=', Carbon::parse($filters['dt_ini']));
            $query->whereDate('forecast', '<=', Carbon::parse($filters['dt_end']));
        });

        $query->when(!($filters['search'] || $filters['funnel'] || $filters['type'] || $filters['dt_ini'] && $filters['dt_end']), function ($query) use ($filters) {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $query->whereMonth('forecast', '=', $currentMonth);
            $query->whereYear('forecast', '=', $currentYear);
        });
    }

    public function scopeFilterCommission($query, array $filters): void
    {

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('client', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
                $query->orWhere('identify', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['operator'] ?? null, function ($query, $type) {
            $query->where('operator', $type);
        });
//
        $query->when(!($filters['search'] || $filters['month'] || $filters['operator']), function ($query) use ($filters) {
            // Se não houver filtro de busca ou status, filtrar pelo mês atual
            $query->whereMonth('activate', '=', Carbon::now()->month);
        });
//
        $query->when($filters['month'], function ($query) use ($filters) {
            // Se datas de início e fim estão presentes, aplicar o filtro
            $query->whereYear('activate', '=', Carbon::parse($filters['month'])->format('Y'))
                ->whereMonth('activate', '=', Carbon::parse($filters['month'])->format('m'));
        });
    }


    public function scopeFilterOrders($query, array $filters): void
    {

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('client', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
                $query->orWhere('identify', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->whereIn('status_id', $status);
        });

        $query->when($filters['type'] ?? null, function ($query, $type) {
            $query->whereIn('order_type', $type);
        });

        $query->when($filters['dt_ini'] && $filters['dt_end'], function ($query) use ($filters) {
            // Se datas de início e fim estão presentes, aplicar o filtro
            $query->whereDate('forecast', '>=', Carbon::parse($filters['dt_ini']));
            $query->whereDate('forecast', '<=', Carbon::parse($filters['dt_end']));
        });

        $query->when(!($filters['search'] || $filters['status'] || $filters['type'] || $filters['dt_ini'] && $filters['dt_end']), function ($query) use ($filters) {
            // Se não houver filtro de busca ou status, filtrar pelo mês atual
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $query->whereMonth('forecast', '=', $currentMonth);
            $query->whereYear('forecast', '=', $currentYear);
        });
    }

    public function scopeFilterWallets($query, array $filters): void
    {

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('client', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
                $query->orWhere('identify', 'like', '%' . $search . '%');
            });
        });

//        $query->when(!($filters['search'] || $filters['dt_ini'] && $filters['dt_end']), function ($query) use ($filters) {
//            // Se não houver filtro de busca ou status, filtrar pelo mês atual
//            $query->whereMonth('forecast', '=', Carbon::now()->month);
//        });


        $query->when($filters['dt_ini'] && $filters['dt_end'], function ($query) use ($filters) {
            // Se datas de início e fim estão presentes, aplicar o filtro
            $query->whereDate('renew_date', '>=', Carbon::parse($filters['dt_ini']));
            $query->whereDate('renew_date', '<=', Carbon::parse($filters['dt_end']));
        });
    }

    public function scopeFilterArchives($query, array $filters): void
    {

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('client', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
                $query->orWhere('identify', 'like', '%' . $search . '%');
            });
        });


        $query->when($filters['dt_ini'] && $filters['dt_end'], function ($query) use ($filters) {
            // Se datas de início e fim estão presentes, aplicar o filtro
            $query->whereDate('forecast', '>=', Carbon::parse($filters['dt_ini']));
            $query->whereDate('forecast', '<=', Carbon::parse($filters['dt_end']));
        });
    }
}
