<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Helper\SearchPaginate;

class Category extends Model
{
    use HasFactory, SearchPaginate;

    static $search_columns = ['name'];

    protected $fillable = ['name'];
}
