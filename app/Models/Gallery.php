<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Accesor gallery image
     */
    protected function galleryImage() : Attribute
    {
        return Attribute::make(
            get: fn($value) => asset('storage/' . $value)
        );
    }
}
