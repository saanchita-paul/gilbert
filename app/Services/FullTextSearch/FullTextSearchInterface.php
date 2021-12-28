<?php

namespace App\Services\FullTextSearch;

use Illuminate\Database\Eloquent\Builder;

/**
 *
 */
interface FullTextSearchInterface
{
    /**
     * @param Builder $builder
     * @param FullTextQueryInterface[] $queries
     * @return Builder
     */
    public function applyAndSearches(Builder $builder, array $queries): Builder;

    /**
     * @param Builder $builder
     * @param FullTextQueryInterface[] $queries
     * @return Builder
     */
    public function applyOrSearches(Builder $builder, array $queries): Builder;

    /**
     * @param Builder $builder
     * @param FullTextQueryInterface $query
     * @param string $conditionType
     *
     * @return Builder
     */
    public function applySearch(Builder $builder, FullTextQueryInterface $query, string $conditionType): Builder;

    /**
     * @param string $text
     * @return string
     */
    public function fullTextWildCards(string $text): string;

    /**
     * @return string
     */
    public function getSearchMode(): string;

    /**
     * @param string $text
     * @return void
     */
    public function setSearchMode(string $text): void;
}
