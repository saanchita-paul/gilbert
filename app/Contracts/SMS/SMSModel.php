<?php

namespace App\Contracts\SMS;

/**
 *
 */
interface SMSModel
{
    /**
     * Get SMS content
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * set sms content
     *
     * @param string|null $content
     *
     * @return $this
     */
    public function setContent(?string $content): static;

    /**
     * get sms receiver phone
     *
     * @return string|null
     */
    public function getPhoneNumber(): ?string;

    /**
     * set receiver phone
     *
     * @param string|null $phone
     *
     * @return $this
     */
    public function setPhoneNumber(?string $phone): static;

    /**
     * creating new instance
     *
     * @param string|null $phone
     * @param string $content
     *
     * @return static
     */
    public static function createNew(?string $phone, string $content): static;
}
