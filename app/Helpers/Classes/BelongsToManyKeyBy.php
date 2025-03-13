<?php

namespace App\Helpers\Classes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BelongsToManyKeyBy extends BelongsToMany
{
    protected string $keyBy;

    public function __construct(
        $keyBy,
        Builder $query,
        Model $parent,
        string $table,
        string $foreignPivotKey,
        string $relatedPivotKey,
        string $parentKey,
        string $relatedKey,
        string $relationName
    ) {
        $this->keyBy = $keyBy;
        parent::__construct($query, $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relationName);
    }

    protected function buildDictionary($results): array
    {
        $dictionary = parent::buildDictionary($results);

        foreach ($dictionary as $parentKey => $items) {
            $keyedItems = [];
            foreach ($items as $item) {
                $key = $item->{$this->keyBy};
                $keyedItems[$key] = $item;
            }
            $dictionary[$parentKey] = $keyedItems;
        }

        return $dictionary;
    }
}
