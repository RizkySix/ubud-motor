<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'uuid';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';


    /**
     * Relation hasMany booking detail
     */
    public function detail()
    {
        return $this->hasMany(BookingDetail::class);
    }


    /**
     * Setter information booking package
     */
    public static function booking_package_information(string $package , int $duration , int $rentalDuration = null) : string
    {
        $packageFormat = '';
        if($rentalDuration){
            $duration = $duration * $rentalDuration;
        }

        $packageFormat = $package . ' ' . '(' . $duration  . ' days' . ')';
        return $packageFormat;
        
    }

     /**
     * Getter for amount
     */
    protected function amount() : Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value , 2 , '.' , '')
        );
    }
}
