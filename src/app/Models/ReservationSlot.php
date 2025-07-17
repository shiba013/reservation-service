<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ReservationSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'date',
        'reserve_start',
        'reserve_end',
        'max_number',
        'max_group',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function getReserveStartAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getReserveEndAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function setReserveStartAttribute($value)
    {
        $this->attributes['reserve_start'] = Carbon::parse($value)->format('H:i');
    }

    public function setReserveEndAttribute($value)
    {
        $this->attributes['reserve_end'] = Carbon::parse($value)->format('H:i');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'reservation_slot_id');
    }

    public function reservedNumber() :int
    {
        return $this->reservations()->sum('number');
    }
}
