<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Relation belongsto Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }


    /**
     * Relation hasOne Rental extension
     */
    public function rental_extension()
    {
        return $this->hasOne(RentalExtension::class);
    }

    /**
     * Accesor rental date
     */
    protected function rentalDate() : Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('Y-M-d H:i:s'),
            set: fn(string $value) => Carbon::parse($value)->format('Y-m-d H:i:s')
        );
    }

    /**
     * Accesor return date
     */
    protected function returnDate() : Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('Y-M-d H:i:s'),
            set: fn(string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

     /**
     * Accesor Expired payment
     */
    protected function expiredPayment() : Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('Y-M-d H:i:s'),
            set: fn(string $value) => Carbon::parse($value)->format('Y-m-d H:i:s')
        );
    }
}
