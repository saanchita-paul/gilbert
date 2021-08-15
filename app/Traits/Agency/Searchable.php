<?php
namespace App\Traits\Agency;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    /**
     * @var mixed|null
     */
    protected ?string $search;

    public function setSearch(?string $search): void
    {
        $this->search = empty($search) ? null : $search;
    }


    /**
     * applying search
     *
     * @param Builder $builder
     * @param string $searchFrom
     *
     * @return Builder
     */
    //todo: replace this 'like' search with FULL-TEXT-SEARCH
    private function applySearch(Builder $builder, string ...$searchFrom): Builder

    {
        if (!$this->search) {
            return $builder;
        }
        $keys = explode(' ', $this->search);
        return $builder->where(function (Builder $builder) use ($keys, $searchFrom) {
            foreach ($keys as $key) {
                foreach($searchFrom as $s) {
                    $builder->orWhere($s, 'like', '%' . $key . '%');
                }
            }
        });
    }
}
