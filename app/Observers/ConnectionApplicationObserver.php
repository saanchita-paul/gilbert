<?php

namespace App\Observers;

use App\Models\ConnectionApplication;
use App\Services\Agency\TriageFlagService;
use App\Services\DuplicateApplication\DuplicationApplicationService;

class ConnectionApplicationObserver
{
    /**
     * Handle the ConnectionApplication "created" event.
     *
     * @param ConnectionApplication $connectionApplication
     * @return void
     */
    public function created(ConnectionApplication $connectionApplication)
    {
        TriageFlagService::setTriageFlag($connectionApplication->id);


    }

    /**
     * @param ConnectionApplication $connectionApplication
      * @return void
     */

    public function creating(ConnectionApplication $connectionApplication){


        $duplicatedKey = ($this->afterCreatedApplication($connectionApplication->toArray()))->updateDuplicatedApp();
        $connectionApplication->is_duplicate = true;
        $connectionApplication->duplication_group_id = $duplicatedKey;
    }

    /**
     * Handle the ConnectionApplication "updated" event.
     *
     * @param ConnectionApplication $application
     * @return bool
     */
    public function updated(ConnectionApplication $application)
    {
        foreach (TriageFlagService::MANDATORY_APP_FIELDS_NOT_HOOD_AI as $field) {
            if ($application->isDirty($field)) {
               return TriageFlagService::setTriageFlag($application->id);
            }
        }

//        foreach (DuplicationApplicationService::DUPLICATED_FIELD as $field) {
//            $data = $application->toArray();
//            if($application->isDirty($field)) {
//               $updateService =  match ($field) {
//                    'email' => ($this->updateEmailField($data)),
//                    'phone', 'home_phone' => $this->updatePhoneNumber($data, $field),
//                    'street_number', 'street_name', 'city', 'postcode', 'state', 'country' => $this->updateAddress($data, $field),
//                    'default' => null,
//                };
//
//               !empty($updateService) && $updateService->updateDuplicatedApp();
//            }
//            if ($application->isDirty($field) && $field === 'email') {
//                return TriageFlagService::setTriageFlag($application->id);
//            }
//            if ($application->isDirty($field) && ($field === 'phone' || $field === 'home_phone')) {
//                return TriageFlagService::setTriageFlag($application->id);
//            }
//        }
    }

    /**
     * Handle the ConnectionApplication "deleted" event.
     *
     * @param ConnectionApplication $connectionApplication
     * @return void
     */
    public function deleted(ConnectionApplication $connectionApplication)
    {
        //
    }

    /**
     * Handle the ConnectionApplication "restored" event.
     *
     * @param ConnectionApplication $connectionApplication
     * @return void
     */
    public function restored(ConnectionApplication $connectionApplication)
    {
        //
    }

    /**
     * Handle the ConnectionApplication "force deleted" event.
     *
     * @param ConnectionApplication $connectionApplication
     * @return void
     */
    public function forceDeleted(ConnectionApplication $connectionApplication)
    {
        //
    }

//    /**
//     * @param $data
//     * @return DuplicationApplicationService
//     */
//    private function updateEmailField($data): DuplicationApplicationService
//    {
//        $datum = $this->prepareDuplicatedKeys($data);
//       return  (new DuplicationApplicationService($datum, false, false, true, false));
//    }

//    /**
//     * @param $data
//     * @param $field
//     * @return DuplicationApplicationService
//     */
//    private function updatePhoneNumber($data, $field): DuplicationApplicationService
//    {
//        $datum = $this->prepareDuplicatedKeys($data);
//
//        // phone related 3 field exist so we need active field onley
//        $datum['phone'] = $data[$field];
//        return  (
//            new DuplicationApplicationService(
//            $datum,
//            false,
//            true,
//            false,
//            false)
//        );
//    }

//    /**
//     * @param array $data
//     * @return DuplicationApplicationService
//     */
//    private function updateAddress(array $data): DuplicationApplicationService
//    {
//        $datum = $this->prepareDuplicatedKeys($data);
//        return  (
//            new DuplicationApplicationService(
//            $datum,
//            false,
//            false,
//            false,
//            true)
//        );
//    }

    /**
     * @param array $appData
     * @return array
     */
    private function prepareDuplicatedKeys(array $appData): array
    {
        $datum = DuplicationApplicationService::DUPLICATED_DATA_ARRAY;
        return array_merge($datum, array_intersect_key($appData, $datum));
    }

    /**
     * @param array $data
     * @return DuplicationApplicationService
     */
    private function afterCreatedApplication(array $data): DuplicationApplicationService
    {
        $datum = $this->prepareDuplicatedKeys($data);
        if($data['phone_type'] === ConnectionApplication::PHONE_TYPE_HOMEPHONE) {
            $datum['phone']  = $data['homephone'];
        }
        return  (new DuplicationApplicationService($datum, true));

    }
}
