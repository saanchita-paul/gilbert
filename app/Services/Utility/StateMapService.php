<?php


namespace App\Services\Utility;


class StateMapService
{

    const STATElIST = [
        'New South Wales' => 'NSW',
        'Victoria' => 'VIC',
        'Queensland' => 'QLD',
        'South Australia' => 'SA',
        'Northern Territory' => 'NT',
        'Tasmania' => 'TAS',
        'Australian Capital Territory' => 'ACT',
        'Western Australia' => 'WA',
    ];

    public function getShortName($state)
    {
        return self::STATElIST[$state];
    }

    public function getFullName($state)
    {
        return array_search($state, self::STATElIST);
    }


}
