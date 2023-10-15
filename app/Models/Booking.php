<?php

namespace App\Models;

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
}
