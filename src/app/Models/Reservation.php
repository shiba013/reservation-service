<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'date',
        'time',
        'number',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function getTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function setTimeAttribute($value)
    {
        $this->attributes['time'] = Carbon::parse($value)->format('H:i');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
