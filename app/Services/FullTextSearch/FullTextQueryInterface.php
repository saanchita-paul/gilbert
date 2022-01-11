<?php

namespace App\Services\FullTextSearch;

/**
 *
 */
interface FullTextQueryInterface
{
    /**
     * creating a new instance
     *
     * @param string $text
     * @param string $index
     * @param int $priority
     *
     * @return static
     */
    public function createNew(string $text, string $index, int $priority): static;

    /**
     * @return string
     */
    public function getSearchText(): string;

    /**
     * @param string $searchText
     * @return void
     */
    public function setSearchText(string $searchText): void;

    /**
     * @return string
     */
    public function getIndex(): string;

    /**
     * @param string $index
     * @return void
     */
    public function setIndex(string $index): void;

    /**
     * @return int
     */
    public function getPriority(): int;

    /**
     * @param int $priority
     * @return void
     */
    public function setPriority(int $priority): void;
}
