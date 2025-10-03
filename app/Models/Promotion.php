<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // ✅ Import at top

class Promotion extends Model
{
    protected $table = 'promotions';

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
        'penumpang_id',
        'rute_id',
        'rowstatus',
        'created_by',
        'created_date',
        'modified_by',
        'modified_date',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'created_date' => 'datetime',
        'modified_date' => 'datetime',
        'is_active' => 'boolean',
        'rowstatus' => 'integer',
    ];

    /**
     * Boot method to hook into creation/update lifecycle
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-set on creation
        static::creating(function ($promotion) {
            $promotion->created_by = $promotion->created_by ?? Auth::id();
            $promotion->created_date = $promotion->created_date ?? now();
            $promotion->rowstatus = $promotion->rowstatus ?? 0;
        });

        // Auto-set on update
        static::updating(function ($promotion) {
            $promotion->modified_by = Auth::id();
            $promotion->modified_date = now();
        });
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'penumpang_id');
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'rute_id');
    }

    /**
     * Enhanced validation logic
     */
    public function isValid($userId = null, $ruteId = null)
    {
        // Soft-deleted?
        if ($this->rowstatus < 0) {
            return false;
        }

        // Active, not expired, within usage limit
        if (!$this->is_active ||
            ($this->expires_at && now()->gt($this->expires_at)) ||
            $this->used_count >= $this->max_uses) {
            return false;
        }

        // User-specific promo?
        if ($this->penumpang_id && $this->penumpang_id != $userId) {
            return false;
        }

        // Route-specific promo?
        if ($this->rute_id && $this->rute_id != $ruteId) {
            return false;
        }

        return true;
    }

    /**
     * Custom delete: soft delete via rowstatus
     */
    public function delete()
    {
        if ($this->exists) {
            $this->update([
                'rowstatus' => -1,
                'modified_by' => Auth::check() ? Auth::id() : $this->modified_by,
                'modified_date' => now(),
            ]);
        }
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}