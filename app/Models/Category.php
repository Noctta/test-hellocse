<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Enums\CategoryStatus;
use App\Enums\ProductStatus;

class Category extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'image',
        'status',
    ];


    protected $casts = [
        'status' => CategoryStatus::class,
    ];



    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function onlineProducts(): HasMany
    {
        return $this->products()->where('status', ProductStatus::ONLINE);
    }

    
    #[Scope]
    public function scopeOnline(Builder $query): void
    {
        $query->where('status', CategoryStatus::ONLINE);
    }
}
