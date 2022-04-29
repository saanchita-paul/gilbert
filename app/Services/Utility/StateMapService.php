<?php


namespace App\Services\Utility;


use Exception;

/**
 *
 */
class StateMapService
{

    /**
     *
     */
    const FULL_TO_SHORT = [
        'NEW SOUTH WALES' => 'NSW',
        'VICTORIA' => 'VIC',
        'QUEENSLAND' => 'QLD',
        'SOUTH AUSTRALIA' => 'SA',
        'NORTHERN TERRITORY' => 'NT',
        'TASMANIA' => 'TAS',
        'AUSTRALIAN CAPITAL TERRITORY' => 'ACT',
        'WESTERN AUSTRALIA' => 'WA',
    ];

    /**
     *
     */
    const SHORT_TO_FULL = [
        'NSW' => 'NEW SOUTH WALES',
        'VIC' => 'VICTORIA',
        'QLD' => 'QUEENSLAND',
        'SA' => 'SOUTH AUSTRALIA',
        'NT' => 'NORTHERN TERRITORY',
        'TAS' => 'TASMANIA',
        'ACT' => 'AUSTRALIAN CAPITAL TERRITORY',
        'WA' => 'WESTERN AUSTRALIA',

    ];


    /**
     * Getting short form of state name
     *
     * @example $state = StateMapService::getShortForm('VICTORIA') -> 'VIC'
     * @example $state = StateMapService::getShortForm('Victoria') -> 'VIC'
     * @example $state = StateMapService::getShortForm('VIC') -> 'VIC'
     * @example $state = StateMapService::getShortForm('Vic') -> 'VIC'
     * @example $state = StateMapService::getShortForm('Vica') -> throws Exception
     * @example $state = StateMapService::getShortForm('') -> throws Exception
     *
     * @param string|null $state
     *
     * @return string|void
     *
     * @throws Exception
     */
    public static function getShortName(?string $state)
    {
        if(!$state) {
            self::throwError($state);
        }

        $state = strtoupper($state);
        if(isset(self::SHORT_TO_FULL[$state])) {
            return $state;
        }

        if(isset(self::FULL_TO_SHORT[$state])) {
            return self::FULL_TO_SHORT[$state];
        }

        self::throwError($state);

    }

    /**
     * Getting full form of state name
     *
     * @example getFullName('nws') -> 'New South Wales'
     * @example getFullName('NSW') -> 'New South Wales'
     * @example getFullName('New South Wales') -> 'New South Wales'
     * @example getFullName('NEW SOUTH WALES') -> 'New South Wales'
     * @example $state = StateMapService::getShortForm('unknown') -> throws Exception
     * @example $state = StateMapService::getShortForm('') -> throws Exception
     *
     * @param string|null $state
     *
     * @return string
     *
     * @throws Exception
     */
    public static function getFullName(?string $state): string
     {
         if(!$state) {
             self::throwError($state);
         }

         $state = strtoupper($state);
         if(isset(self::FULL_TO_SHORT[$state])) {
             return self::formatFullForm($state);
         }

         if(isset(self::SHORT_TO_FULL[$state])) {
             return self::formatFullForm(self::SHORT_TO_FULL[$state]);
         }

         self::throwError($state);
     }

    /**
     * Formatting to Title case
     *
     * @param string $state
     *
     * @return string
     */
     private static function formatFullForm(string $state): string
     {
         return ucwords(strtolower($state));
     }


    /**
     * @throws Exception
     */
    public static function throwError(?string $state)
     {
         throw new Exception('[StateMapService] Unable to map state: ' . $state);
     }

}
