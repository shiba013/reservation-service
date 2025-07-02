<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'genre_id',
        'name',
        'image',
        'overview',
        'start_time',
        'end_time',
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

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, );
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservation_slots()
    {
        return $this->hasMany(reservation_slot::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if(!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
    }

    public function scopeAreaSearch($query, $area_id)
    {
        if(!empty($area_id)) {
            $query->where('area_id', $area_id);
        }
    }

    public function scopeGenreSearch($query, $genre_id)
    {
        $query->where('genre_id', $genre_id);
    }
}
