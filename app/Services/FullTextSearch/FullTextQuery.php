<?php

namespace App\Services\FullTextSearch;

use JetBrains\PhpStorm\Pure;

class FullTextQuery implements FullTextQueryInterface
{
    const INDEX_FT_TENANT_NAME = 'ft_tenant_name';
    const INDEX_FT_ADDRESS= 'ft_address';
    const INDEX_FT_PHONE= 'ft_phone';

    const mapIndex = [];

    public function __construct(
        private ?string $text = null,
        private ?string $index = null,
        private int $priority = 1
    ) {}

    /**
     * @param string $text
     * @param string $index
     * @param $priority
     *
     * @return static
     */
    #[Pure]
    public function createNew(string $text, string $index, $priority = 1): static
    {
        return new static($text, $index, $priority);
    }

    public function getClass(): string
    {
        return FullTextQuery::class;
    }

    /**
     * @return string
     */
    public function getSearchText(): string
    {
        return $this->text;
    }

    /**
     * @param string $searchText
     */
    public function setSearchText(string $searchText): void
    {
        $this->text = $searchText;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @param string $index
     */
    public function setIndex(string $index): void
    {
        $this->index = $index;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }


}
