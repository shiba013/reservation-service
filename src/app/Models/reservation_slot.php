<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class reservation_slot extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'date',
        'start_time',
        'end_time',
        'max_number',
        'max_group',
        'is_active',
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
        $this->attributes['start_time'] = Carbon::parse($value)->format('H:i');
        $this->attributes['end_time'] = Carbon::parse($value)->format('H:i');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
