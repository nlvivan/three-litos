<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait Searchable
{
    public function scopeSearch($query, $search)
    {
        if (! $search) {
            return $query;
        }

        $search = strtolower(trim($search)); // Clean up white space
        $fields = $this->searchable ?: [];

        if (Str::of($search)->isEmpty()) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search, $fields) {
            foreach ($fields as $key => $field) {
                if (Str::contains($field, '.')) {
                    $arr = explode('.', $field);
                    $relationshipField = array_pop($arr);
                    $relationship = Arr::join($arr, '.');
                    $query->orWhereHas($relationship, function ($query) use ($relationshipField, $search) {
                        $query->whereRaw("LOWER($relationshipField) like ?", "%$search%");
                    });
                } else {
                    $query->orWhereRaw("LOWER($field) like ?", "%$search%");
                }
            }
        });
    }
}
