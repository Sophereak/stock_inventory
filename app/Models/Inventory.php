<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
        'clothing_type',
        'size',
        'gender',
        'color',
        'school_house'
    ];

    // Remove the price cast since we're using integers now
    // protected $casts = [
    //     'price' => 'decimal:2',
    // ];

    // Define clothing types for easy reference
    const CLOTHING_TYPES = [
        'shirt' => 'Shirt',
        'pants' => 'Pants',
        'skirt' => 'Skirt',
        'dress' => 'Dress',
        'sweater' => 'Sweater',
        'jacket' => 'Jacket',
        'uniform' => 'Uniform',
        'pe_kit' => 'PE Kit',
        'tie' => 'Tie',
        'blazer' => 'Blazer',
    ];

    // Define sizes
    const SIZES = [
        'XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 
        '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16'
    ];

    // Define genders
    const GENDERS = [
        'boys' => 'Boys',
        'girls' => 'Girls',
        'unisex' => 'Unisex'
    ];

    // Format price as Riel with comma separators
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' ៛';
    }
}