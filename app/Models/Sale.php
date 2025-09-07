<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'inventory_id',
        'quantity_sold',
        'unit_price',
        'total_amount',
    ];

    /**
     * Relation: A Sale belongs to one Inventory item.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        // When a sale is created → subtract stock
        static::created(function ($sale) {
            if ($sale->inventory) {
                $sale->inventory->decrement('quantity', $sale->quantity_sold);
            }
        });

        // When a sale is updated → adjust stock correctly
        static::updating(function ($sale) {
            if ($sale->inventory) {
                $originalQuantity = $sale->getOriginal('quantity_sold');
                $newQuantity      = $sale->quantity_sold;
                $difference       = $newQuantity - $originalQuantity;

                if ($difference > 0) {
                    // More items sold → reduce stock further
                    $sale->inventory->decrement('quantity', $difference);
                } elseif ($difference < 0) {
                    // Sale reduced → restore stock
                    $sale->inventory->increment('quantity', abs($difference));
                }
            }
        });

        // When a sale is deleted → restore stock
        static::deleting(function ($sale) {
            if ($sale->inventory) {
                $sale->inventory->increment('quantity', $sale->quantity_sold);
            }
        });
    }
}
