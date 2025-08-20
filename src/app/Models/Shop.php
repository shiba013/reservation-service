<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'area_id',
        'genre_id',
        'name',
        'image',
        'overview',
        'start_time',
        'end_time',
    ];

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = Carbon::parse($value)->format('H:i');
    }

    public function setEndTimeAttribute($value)
    {
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
        return $this->hasMany(ReservationSlot::class);
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

    public function scopeAreaSearch($query, $area_area)
    {
        if(!empty($area_area)) {
            $query->whereHas('area', function ($q) use ($area_area) {
                $q->where('area', $area_area);
            });
        }
    }

    public function scopeGenreSearch($query, $genre_genre)
    {
        if(!empty($genre_genre)) {
            $query->whereHas('genre', function ($q) use ($genre_genre) {
                $q->where('genre', $genre_genre);
            });
        }
    }
}
