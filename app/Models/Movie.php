<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'serie_id', 'name', 'description', 'video', 'image', 'serie_order'
    ];

    public function serie() {
        return $this->belongsTo(Serie::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
