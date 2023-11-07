<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalExtension extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'uuid';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Relation belongsto Booking detail
     */
    public function booking_detail()
    {
        return $this->belongsTo(BookingDetail::class);
    }

    /**
     * Accesor extension from
     */
    protected function extensionFrom() : Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('Y-M-d H:i:s'),
            set: fn(string $value) => Carbon::parse($value)->format('Y-m-d H:i:s')
        );
    }

    /**
     * Accesor extension to
     */
    protected function extensionTo() : Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('Y-M-d H:i:s'),
            set: fn(string $value) => Carbon::parse($value)->format('Y-m-d H:i:s')
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
     * Accesor for amount
     */
    protected function amount() : Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value , 2 , '.' , '')
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

    /**
     * Accesor is Confirmed
     */
    protected function isConfirmed() : Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value
        );
    }
}
