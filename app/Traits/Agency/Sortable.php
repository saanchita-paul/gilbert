<?php

namespace App\Traits\Agency;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    /**
     * @var string|null
     */
    protected ?string $sortBy;

    /**
     * @var string|null
     */
    protected ?string $sortDir;

    /**
     * @param string|null $sortBy
     * @param string|null $sortDir
     */
    public function setSortBy(?string $sortBy, ?string $sortDir): void
    {
        $this->sortBy = empty($sortBy) ? null : $sortBy;
        $this->sortDir = $sortDir === 'true' ? 'desc' : 'asc';
    }


    /**
     * applying sorting
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    private function applySorting(Builder $builder): Builder
    {
        if ($this->sortBy) {
            return $builder->orderBy($this->sortBy, $this->sortDir);
        }
        return $builder;
    }
}
