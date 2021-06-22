<?php

namespace App\Helper;

trait SearchPaginate {
    public function scopeSearchAndPaginate($query) {
        $request = app()->make('request');

        return $query->where(function($q) use ($request) {
            if ($request->has('search')) {
                collect(self::$search_columns)->map(function($column) use ($q, $request) {
                    $q->orWhere($column, 'LIKE', '%'.$request->search.'%');
                });
            }
        })->paginate(20);
    }
}