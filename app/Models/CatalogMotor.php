<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogMotor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    /**
     * Relation one to one CatalogMotor to CatalogPrice
     */
    public function price()
    {
        return $this->hasOne(CatalogPrice::class);
    }
}
