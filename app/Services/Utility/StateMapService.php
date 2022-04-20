<?php


namespace App\Services\Utility;


class StateMapService
{

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

    

    public static function getShortName($state)
    {
        if(!$state) {
            throw new \Exception('Unable to map' . $state);
        }

        $state = strtoupper($state);
        if(isset(self::SHORT_TO_FULL[$state])) {
            return $state;
        }

        if(isset(self::FULL_TO_SHORT[$state])) {
            return self::FULL_TO_SHORT[$state];
        }

        throw new \Exception('Unable to map' . $state);

    }

    // public static function getFullName($state)
    // {
    //     if(!$state) {
    //         throw new \Exception('Unable to map' . $state);
    //     }

    //     $state = strtoupper($state);
    //     if(isset(self::SHORT_TO_FULL[$state])) {
    //         return self::SHORT_TO_FULL[$state];
    //     }

    //     if(isset(self::FULL_TO_SHORT[$state])) {
    //         return $state;
    //     }

    //     throw new \Exception('Unable to map ' . $state);
    // }


    // public function mapShortForm($state)
    // {
    //     $upperCaseState = strtoupper($state);
    //     $upperCaseStateLists = array_map('strtoupper', self::STATElIST);
    //     foreach($upperCaseStateLists as $key => $value){
    //         if($upperCaseState === $value) {
    //             return $state;
    //         } else {
    //             return $this->getShortName($state);
    //         }
    //     }
    // }
}
