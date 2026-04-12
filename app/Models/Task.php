<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const STATUS_TODO = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_DONE = 2;

    protected $fillable = [
        'title',
        'description',
        'category',
        'priority',
        'status',
        'sort_order',
        'created_by',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCategoryBadgeAttribute()
    {
        return match ($this->category) {
            'bug' => '<span class="badge bg-danger">🐛 Bug</span>',
            'improvement' => '<span class="badge bg-warning text-dark">⚡ Improvement</span>',
            'feature' => '<span class="badge bg-success">🌟 Feature</span>',
            'idea' => '<span class="badge bg-info">💡 Idea</span>',
            default => '<span class="badge bg-secondary">Other</span>',
        };
    }

    public function getPriorityBadgeAttribute()
    {
        return match ($this->priority) {
            'critical' => '<span class="badge bg-danger">Critical</span>',
            'high' => '<span class="badge bg-warning text-dark">High</span>',
            'medium' => '<span class="badge bg-info">Medium</span>',
            'low' => '<span class="badge bg-secondary">Low</span>',
            default => '',
        };
    }
}
