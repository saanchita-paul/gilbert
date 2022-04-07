<?php


namespace App\Services\Address;

class LookUpOptions{
    public function __construct(
        public ?string $country = null,
        public ?string $sourceOfTruth = null,
        public ?string $baseUrl = null,
    )
    {
        $this->country = $country;
        $this->sourceOfTruth = $sourceOfTruth;
        $this->baseUrl = $baseUrl;
    }
}