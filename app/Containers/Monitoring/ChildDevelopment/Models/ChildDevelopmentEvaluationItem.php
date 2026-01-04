<?php

namespace App\Containers\Monitoring\ChildDevelopment\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ChildDevelopmentEvaluationItem extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'evaluation_id',
        'development_item_id',
        'achieved',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'achieved' => 'boolean',
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the evaluation for this item result.
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(ChildDevelopmentEvaluation::class, 'evaluation_id');
    }

    /**
     * Get the development item.
     */
    public function developmentItem(): BelongsTo
    {
        return $this->belongsTo(DevelopmentItem::class, 'development_item_id');
    }

    // ==========================================================================
    // Helper Methods
    // ==========================================================================

    /**
     * Check if the item was achieved.
     */
    public function isAchieved(): bool
    {
        return $this->achieved === true;
    }

    /**
     * Mark item as achieved.
     */
    public function markAsAchieved(): void
    {
        $this->achieved = true;
        $this->save();
    }

    /**
     * Mark item as not achieved.
     */
    public function markAsNotAchieved(): void
    {
        $this->achieved = false;
        $this->save();
    }
}

