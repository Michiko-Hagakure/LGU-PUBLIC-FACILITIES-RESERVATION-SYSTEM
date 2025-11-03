<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'target_audience',
        'is_active',
        'is_pinned',
        'start_date',
        'end_date',
        'created_by',
        'attachment_path',
        'additional_info'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    // --- Relationships ---

    /**
     * Get the user who created the announcement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // --- Scopes ---

    /**
     * Scope to get active announcements only (within date range).
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }
    
    /**
     * Scope to filter announcements by target audience.
     */
    public function scopeForAudience($query, string $audience)
    {
        return $query->where('target_audience', $audience);
    }

    /**
     * Scope to order by priority (e.g., urgent first).
     */
    public function scopeByPriority($query)
    {
        return $query->orderByRaw("CASE 
                                     WHEN priority = 'urgent' THEN 1 
                                     WHEN priority = 'high' THEN 2 
                                     WHEN priority = 'medium' THEN 3 
                                     WHEN priority = 'low' THEN 4 
                                     ELSE 5 
                                  END");
    }

    // --- Accessors ---

    /**
     * Check if announcement is currently active and within its date range.
     */
    public function isActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->start_date && $this->start_date->greaterThan($now)) {
            return false; // Not yet started
        }

        if ($this->end_date && $this->end_date->lessThan($now)) {
            return false; // Expired
        }

        return true;
    }

    /**
     * Check if announcement is expired
     */
    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    /**
     * Get priority badge color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get type badge color.
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'urgent' => 'bg-red-100 text-red-800',
            'maintenance' => 'bg-orange-100 text-orange-800',
            'event' => 'bg-blue-100 text-blue-800',
            'facility_update' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}