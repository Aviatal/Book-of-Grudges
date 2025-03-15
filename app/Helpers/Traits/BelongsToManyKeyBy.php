<?php

namespace App\Helpers\Traits;

trait BelongsToManyKeyBy
{
    public function belongsToManyKeyBy(
        string $keyBy,
        string $related,
        string $table,
        string $foreignPivotKey,
        string $relatedPivotKey,
        string $parentKey,
        string $relatedKey,
        string $relationName
    ): \App\Helpers\Classes\BelongsToManyKeyBy {
        $instance = $this->newRelatedInstance($related);

        $foreignPivotKey = $foreignPivotKey ?: $this->getForeignKey();
        $relatedPivotKey = $relatedPivotKey ?: $instance->getForeignKey();

        if (is_null($table)) {
            $table = $this->joiningTable($instance);
        }

        return new \App\Helpers\Classes\BelongsToManyKeyBy(
            $keyBy,
            $instance->newQuery(),
            $this,
            $table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey,
            $relationName
        );
    }
}
