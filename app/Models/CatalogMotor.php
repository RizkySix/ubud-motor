<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        return $this->hasMany(CatalogPrice::class);
    }

    public function getRouteKeyName()
    {
        return 'motor_name';
    }

    /**
     * Accesor for createad_at
     */
    protected function createdAt() : Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->diffForHumans()
        );
    }

    /**
     * Accesor first catalog
     */
    protected function firstCatalog() : Attribute
    {
       return $this->catalogImageParse();
    }

    /**
     * Accesor second catalog
     */
    protected function secondCatalog() : Attribute
    {
       return $this->catalogImageParse();
    }

    /**
     * Accesor third catalog
     */
    protected function thirdCatalog() : Attribute
    {
       return $this->catalogImageParse();
    }

    /**
     * Accesor method catalog
     */
    protected function catalogImageParse() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? asset('storage/' . $value) : null
        );
    }
}
