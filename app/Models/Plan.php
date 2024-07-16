<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * @method static find($id)
 * @method static create(array $plan)
 */
class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'slug'];

    public function modules(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_plan', 'plan_id', 'module_id')->orderBy('label');
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function modulesAvailable(array $filters = [])
    {
        return Module::whereNotIn('id', function ($query) {
            $query->select('module_plan.module_id');
            $query->from('module_plan');
            $query->whereRaw("module_plan.plan_id={$this->id}");
        })
            ->filter($filters)
            ->paginate();
    }

    public function scopeFilter($query, array $filters): void
    {
//        $query->when($filters['search'] ?? null, function ($query, $search) {
//            $query->where('name', 'LIKE', '%' . $search . '%');
//        });
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhereHas('modules', function ($query) use ($search) {
                    $query->where('label', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%');
                });
        });
    }


    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => number_format($value, 2, ',', '.'),
//            set: fn(string $value) => floatval(str_replace(',', '.', str_replace('.', '', $value)))
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('d/m/Y'),
        );
    }

}
