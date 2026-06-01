<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\ProductStatus;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'status',
        'category_id',
    ];

    protected $casts = [
        'status' => ProductStatus::class,
        'price'  => 'decimal:2',
    ];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeOnline(Builder $query): void
    {
        $query->where('status', ProductStatus::ONLINE);
    }
}