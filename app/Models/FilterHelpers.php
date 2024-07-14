<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;

trait FilterHelpers {


    protected function filterByVal(Builder &$query, array $filter, $key, $tableField = null) : void {
        if (empty($tableField)) {
            $tableField = $key;
        }
        if (!empty($filter[$key])) {
            if (is_array($filter[$key])) {
                $query->whereIn($this->table . '.' . $tableField, $filter[$key]);
            } else if (is_numeric($filter[$key])) {
                $query->where($this->table . '.' . $tableField, '=', $filter[$key]);
            }
        } else if (array_key_exists($key, $filter) && $filter[$key] === null) {
            $query->whereNull($this->table . '.' . $tableField);
        }
    }

}
