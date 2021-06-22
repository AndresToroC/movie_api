<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Helper\SearchPaginate;

class Serie extends Model
{
    use HasFactory, SearchPaginate;

    static $search_columns = ['name'];

    protected $fillable = [
        'name', 'description', 'image', 'director', 'actors'
    ];

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function movies() {
        return $this->hasMany(Movie::class);
    }
}
