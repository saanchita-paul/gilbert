<?php

namespace App\Services\DownloadExcel;

use App\ConnectionService;
use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use function PHPUnit\Framework\matches;

class ExcelFileService
{

    /**
     * @param mixed $get
     */

    public function getExelFileData(array $ids)
    {

        $applications = ConnectionApplication::whereIn('id', $ids)
            ->with('connectionServices','identification','authorizedPerson')
            ->get();
        $mappedApplication = [];
        foreach ($applications as $app) {
            $mappedApplication[] = [
                'Brand' => 'PowerShop',
                'Channel ID' => 'Hood Move Tech',
                'Customer Type' => 'Residential',
                'NMI' => $app->nmi,
                'MIRN' => $app->mirn,
                'Connection Date' => $app->moving_date,
                'Type of Sale' => 'Moving',
                'Signup Type' => $this->getSignUpType($app),
                'Title' => $app->title,
                'First Name' => $app->first_name,
                'Last Name' => $app->last_name,
                'Date of Birth' => $app->dob,
                'Business Name' => null,
                'Phone - Home' => $app->homephone, //?
                'Phone - Office' => null,
                'Phone - Mobile' => $app->phone, //?
                'E-mail' => $app->email,
                'ABN' => null,
                'ACN' => null,
                'ID Number' => $this->getIDNumber($app),
                'ID Expiry date' => $this->getExpiryDate($app),
                'Type of ID' =>  $this->getIDType($app),
                'Concession Card Number' => null,
                'Concession Card Type' => $this->getConcessionCardType($app),
                'Concession Card Expiry Date' => null,
                'Name on Concession Card' => null,
                'Second Person Title' =>  $this->getSecondTitle($app),
                'Second First Name' => $this->getSecondFirstName($app),
                'Second Last Name' => $this->getSecondLastName($app),
                'Second Person DOB' => $this->getSecondDob($app),
                'Supply Address' => $this->getSupplyAddress($app),
                'Supply Suburb' => $app->city,
                'State/Territory' => $app->state,
                'Postal Code' => $app->postcode,
                'Mailing Address' => $this->getMailingAddress($app),
                'Mailing Suburb' => $app->billing_city,
                'Mailing State/Territory' => $app->billing_state,
                'Mailing Postal Code' => $app->billing_postcode,
                'Owner/Renter' => $app->tenancy_type == 1 ? 'Renter' : 'Home Owner',
                'Electricity Promo' => '',
                'Gas Promo' => '',
                'Electricity Already On? (Y/N)' => $app->has_electricity == 1 ? 'Yes' : 'No',
                'Meter Number(s)' => null,
                'Any Hazards' => $app->is_any_unrestrained_animal,
                'Any Access Requirements?' => '',
                'Life Support/Sensitive Load' => $this->getLifeSensitive($app),
                'Advised Main Switch Needs Turning Off?' => 'Yes',
                'Safety Certificate Required?' => 'No',
                'Token' => '',
                'Electricity Offer Status' => null,
                'Electricity Reference Number' => null,
                'Electricity Rejection/Incomplete Reason' => null,
                'Gas Offer Status' => null,
                'Gas Reference Number' => null,
                'Gas Rejection/Incomplete Reason' => null,
                'Other Comments' => null,

            ];
        }
        return $mappedApplication;
    }
    private function getConcessionCardType($app){
        if($app->concession_card_type == 'DVA'){
            return 'DVA Health';
        }
        elseif ($app->concession_card_type == 'HCC'){
            return 'Health Care Card';
        }
        elseif ($app->concession_card_type == 'PCC'){
            return 'Pensioner Concession';
        }
        else {
            return 'Queensland Seniors';
        }
    }
    private function getLifeSensitive($app){
        if ($app->is_gas_life_support == 1 && $app->is_power_life_support == 1){
        return 'Life support elec and gas';
        }
        elseif ($app->is_power_life_support == 1){
            return 'Life support elec';
        }
        else{
            return '';
        }
    }
    private function getSupplyAddress($app)
    {
        if ($app->unit_number != '' || $app->street_number != '' || $app->street_name_only != '' || $app->street_type != '') {
            return $app->unit_number. ' , ' .$app->street_number .' , '. $app->street_name_only .' , '. $app->street_type;
        }
        else {
            return $app->unit_number. ' ' .$app->street_number .' '. $app->street_name_only .' '. $app->street_type;
        }
    }
    private function getMailingAddress($app)
    {
        if ($app->billing_unit_number != '' || $app->billing_street_number != '' || $app->billing_street_name_only != '' || $app->billing_street_type != '')
        {
            return $app->billing_unit_number. ' , ' .$app->billing_street_number .' , '. $app->billing_street_name_only .' , '. $app->billing_street_type;
        }
        else{
            return $app->billing_unit_number. ' ' .$app->billing_street_number .' '. $app->billing_street_name_only .' '. $app->billing_street_type;
        }

    }

    private function identification($app)
    {
     $cardNumber = $app->identification->card_number;
     $expiryDate = $app->identification->expire_date;
     $idType = $app->identification->type;
    }
    private function getIDNumber($app)
    {
        return $app->identification->card_number;
    }
    private function getExpiryDate($app)
    {
        return $app->identification->expire_date;
    }
    private function getIDType($app)
    {
        return match($app->identification->type) {
            1 => 'passport',
            2 => 'driving licence',
            3 => 'medicare',
            default => null
        };
    }
    private function getSecondTitle($app)
    {
       return $app->authorizedPerson->title;
    }
    private function getSecondFirstName($app)
    {
        return $app->authorizedPerson->first_name;
    }
    private function getSecondLastName($app)
    {
        return $app->authorizedPerson->last_name;
    }
    private function getSecondDob($app)
    {
        return $app->authorizedPerson->dob;
    }


    private function getSignUpType($app){

        $connectionService = $app->connectionServices->toArray();
       // dd($connectionService[1]['service_type']);
        foreach ($connectionService as $service){
            if($service['service_type']= 'gas' && $service['service_type'] = 'electricity'){
                return 'Two Fuel';
            }
            else{
                return 'One Fuel';
            }
        }
    }


}
