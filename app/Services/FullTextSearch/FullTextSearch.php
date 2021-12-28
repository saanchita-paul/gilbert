<?php

namespace App\Services\FullTextSearch;

use Illuminate\Database\Eloquent\Builder;

/**
 *
 */
class FullTextSearch implements FullTextSearchInterface
{
    /**
     * @var string
     */
    private string $searchMode = 'BOOLEAN';


    /**
     * Applying search to the builder
     *
     * @param Builder $builder
     * @param FullTextQueryInterface $query
     * @param string $conditionType
     *
     * @return Builder
     */
    public function applySearch(Builder $builder, FullTextQueryInterface $query, string $conditionType = 'and'): Builder
    {
        $methodRaw = strtolower($conditionType) === 'and' ? 'whereRaw' : 'orWhereRaw';
        $text = $this->fullTextWildCards($query->getSearchText());
        $index = $query->getIndex();

//        return $builder->where(fn(Builder $builder) => $builder->$methodRaw("MATCH(" . $index . ") AGAINST( '$text' IN $this->searchMode MODE)"));
        return  $builder->$methodRaw("MATCH(" . $index . ") AGAINST( '$text' IN $this->searchMode MODE)");
    }

    /**
     * Applying "AND" searches to the builder
     *
     * @param Builder $builder
     * @param FullTextQueryInterface[] $queries
     *
     * @return Builder
     */
    public function applyAndSearches(Builder $builder, array $queries): Builder
    {
        foreach ($queries as $query) {
            $builder = $this->applySearch($builder, $query, 'and');
        }

        return $builder;
    }


    /**
     *  Applying "OR" searches to the builder
     * @param Builder $builder
     * @param array $queries
     *
     * @return Builder
     */
    public function applyOrSearches(Builder $builder, array $queries): Builder
    {
        foreach ($queries as $query) {
            $builder = $this->applySearch($builder, $query, 'or');
        }
    }

    /**
     * @param string $text
     * @return string
     */
    public function fullTextWildCards(string $text): string
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $text);

        // removing extra space
        $term = preg_replace('/\s+/', ' ', trim($term));

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            } else {
                $words[$key] = "$word*";
            }
        }

        return implode(' ', $words);
    }

    /**
     * @return string
     */
    public function getSearchMode(): string
    {
        // TODO: Implement getSearchMode() method.
    }

    /**
     * @param string $text
     * @return void
     */
    public function setSearchMode(string $text): void
    {
        // TODO: Implement setSearchMode() method.
    }
}
