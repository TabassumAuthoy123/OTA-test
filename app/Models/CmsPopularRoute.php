<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPopularRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_city',
        'origin_code',
        'destination_city',
        'destination_code',
        'starting_price',
        'image',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starting_price' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('position');
    }
}
