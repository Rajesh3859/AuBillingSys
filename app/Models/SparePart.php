<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_number',
        'name',
        'category_id',
        'brand',
        'compatible_models',
        'unit_price',
        'cost_price',
        'stock_quantity',
        'reorder_level',
        'rack_location',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }
}
