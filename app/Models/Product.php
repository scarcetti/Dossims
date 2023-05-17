<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'product_category_id',
        'measurement_unit_id',
        'ready_made',
    ];

    public function measurementUnit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function branch_product()
    {
        return $this->hasMany(BranchProduct::class);
    }
}
