<?php

namespace App\Plugins\AWS\SMS;

use App\Contracts\SMS\SMSModel;
use JetBrains\PhpStorm\Pure;

/**
 *
 */
class SMS implements SMSModel
{
    /**
     * @param string|null $phone
     * @param string|null $content
     */
    public function __construct(private ?string $phone = null, private ?string $content = null)
    {
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return $this
     */
    public function setContent(?string $content): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return $this
     */
    public function setPhoneNumber(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param string|null $phone
     * @param string $content
     * @return static
     */
    #[Pure]
    public static function createNew(?string $phone, string $content): static
    {
        return new static($phone, $content);
    }
}
