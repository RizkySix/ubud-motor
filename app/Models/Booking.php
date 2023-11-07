<?php

namespace App\Models;

use Carbon\Carbon;
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
    public function booking_detail()
    {
        return $this->hasMany(BookingDetail::class);
    }

    /**
     * Relation belongsTo Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
     * Accesor for amount
     */
    protected function amount() : Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value , 2 , '.' , '')
        );
    }

     /**
     * Accesor for card image
     */
    protected function cardImage() : Attribute
    {
        return Attribute::make(
            get: fn($value) => asset('storage/' . $value)
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

    /**
     * Accesor is confirmed
     */
    protected function isConfirmed() : Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value
        );
    }

    /**
     * Accesor is Active
     */
    protected function isActive() : Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value
        );
    }

    /**
     * Accesor is Notified
     */
    protected function isNotified() : Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value
        );
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
}
