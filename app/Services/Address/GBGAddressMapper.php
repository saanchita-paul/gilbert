<?php

namespace App\Services\Address;

/**
 *
 */
class GBGAddressMapper
{
    /**
     * Map for connection application address
     *
     * @param array|null $address
     * @return array
     */
    public static function toAppAddress(?array $address): array
    {
        if ($address) {
            return [
                'unit_number' => data_get($address, 'flatUnitNumber'),
                'street_number' => data_get($address, 'streetNumber'),
                'street_name_only' => data_get($address, 'streetName'),
                'street_type' => StreetTypeMapper::getShortForm(data_get($address, 'streetType')) ,
                'postcode' => data_get($address, 'postcode'),
                'city' => data_get($address, 'locality'),
                'state' => AddressModel::mapStateToLong(data_get($address, 'state')),
                'country' => 'Australia',
                'address_text' => GBGAddressMapper::fullAddress($address)
            ];
        }
        return [];
    }

    /**
     * Map for connection application billing address
     *
     * @param array $address
     * @return array
     */
    public static function toBillingAddress(array $address): array
    {
        if ($address) {
            return [
                'billing_unit_number' => data_get($address, 'flatUnitNumber'),
                'billing_street_number' => data_get($address, 'streetNumber'),
                'billing_street_name_only' => data_get($address, 'streetName'),
                'billing_street_type' => StreetTypeMapper::getShortForm(data_get($address, 'streetType')) ,
                'billing_postcode' => data_get($address, 'postcode'),
                'billing_city' => data_get($address, 'locality'),
                'billing_state' => AddressModel::mapStateToLong(data_get($address, 'state')),
                'billing_address_text' => GBGAddressMapper::fullAddress($address)
            ];
        }

        return [];
    }

    /**
     * Concatenating to make full address
     *
     * @param array $address
     * @return string
     */
    public static function fullAddress(array $address): string
    {
        $text = data_get($address, 'flatUnitNumber');
        $text .= !empty($text)
            ? '/'.  data_get($address, 'streetNumber')
            : data_get($address, 'streetNumber') ?? "";
        $text .= data_get($address, 'streetNumber') ? " " .  data_get($address, 'streetName') : "";
        $text .= data_get($address, 'streetNumber') ? " " .  data_get($address, 'streetType') . "," : "";
        $text .= data_get($address, 'streetNumber') ? " " .  data_get($address, 'locality') : "";
        $text .= data_get($address, 'streetNumber') ? " " .  data_get($address, 'state') : "";
        $text .= data_get($address, 'streetNumber') ? " " .  data_get($address, 'postcode') . "," : "";

        return trim($text . " Australia");
    }
}
