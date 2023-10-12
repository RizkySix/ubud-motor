<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogPrice extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

     /**
     * Relation one to one CatalogPrice to CatalogMotor
     */
    public function motor()
    {
        return $this->belongsTo(CatalogMotor::class);
    }
}
