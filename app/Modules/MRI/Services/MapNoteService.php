<?php

namespace MRI\Services;

use App\Models\MriApplication;
use App\Models\ConnectionApplication;
use App\Services\Agency\ApplicationNoteService;
use MRI\Services\NotifyMissingDetailsService;
use App\Models\User;
use App\Models\ApplicationNote;
use App\Models\Identification;
use Carbon\Carbon;

class MapNoteService
{
    public const KEY_PASSPORT_NUMBER = [
        'PASSPORT' => 'card_number',
        'PASSPORT NUMBER' => 'card_number',
    ];
    public const KEY_PASSPORT_COUNTRY = [
        'COUNTRY' => 'country',
        'PASSPORT COUNTRY' => 'country',
    ];
    public const KEY_PASSPORT_EXPIRY_DATE = [
        'EXPIRY DATE' => 'expire_date',
        'EXPIRE DATE' => 'expire_date',
        'PASSPORT EXPIRY DATE' => 'expire_date',
        'EXPIRE' => 'expire_date',
        'EXPIRY' => 'expire_date',
    ];
    public const KEY_DRIVERS_LICENSE_NUMBER = [
        'DL' => 'card_number',
        'DRIVERS LICENSE' => 'card_number',
        'DRIVERS NUMBER' => 'card_number',
        'DRIVERS LICENSE NUMBER' => 'card_number',
        'DRIVER LICENSE' => 'card_number',
        'DRIVER LICENSE NUMBER' => 'card_number',
        'DRIVERS LICENCE' => 'card_number',
        'DRIVERS NUMBER' => 'card_number',
        'DRIVERS LICENCE NUMBER' => 'card_number',
        'DRIVER LICENCE' => 'card_number',
        'DRIVER LICENCE NUMBER' => 'card_number',
        'DRIVER NUMBER' => 'card_number',
        'LICENCE NUMBER' => 'card_number',
        'LICENSE NUMBER' => 'card_number',
        'LICENSE' => 'card_number',
        'LICENCE' => 'card_number',
    ];
    public const KEY_DRIVERS_LICENSE_STATE = [
        'STATE' => 'state',
        'DRIVERS LICENSE STATE' => 'state',
        'DRIVERS LICENCE STATE' => 'state',
        'DRIVERS STATE' => 'state',
    ];
    public const KEY_DRIVERS_LICENSE_EXPIRY_DATE = [
        'EXPIRY DATE' => 'expire_date',
        'EXPIRE DATE' => 'expire_date',
        'DL EXPIRY DATE' => 'expire_date',
        'DRIVERS LICENSE EXPIRY DATE' => 'expire_date',
        'DRIVER LICENSE EXPIRY DATE' => 'expire_date',
        'EXPIRE' => 'expire_date',
        'EXPIRY' => 'expire_date',
    ];
    public const KEY_MEDICARE_CARD_NUMBER = [
        'MEDICARE CARD NUMBER' => 'card_number',
        'CARD NUMBER' => 'card_number',
        'CN' => 'card_number',
        'MEDICARE CARD' => 'card_number',
        'MEDICARE NUMBER' => 'card_number',
        'MEDICARE' => 'card_number',
    ];
    public const KEY_MEDICARE_SPECIAL_NUMBER = [
        'MEDICARE SPECIAL NUMBER' => 'special_number',
        'MEDICARE SPECIAL' => 'special_number',
        'SPECIAL NUMBER' => 'special_number',
        'INDIVIDUAL NUMBER' => 'special_number',
        'REFERENCE NUMBER' => 'special_number',
        'SPECIAL' => 'special_number',
        'INDIVIDUAL' => 'special_number',
        'REFERENCE' => 'special_number',
    ];
    public const KEY_MEDICARE_EXPIRY_DATE = [
        'EXPIRY DATE' => 'expire_date',
        'EXPIRE DATE' => 'expire_date',
        'MEDICARE EXPIRY DATE' => 'expire_date',
        'EXPIRE' => 'expire_date',
        'EXPIRY' => 'expire_date',
    ];
    public const KEY_MEDICARE_CARD_COLOUR = [
        'MEDICARE CARD COLOUR' => 'card_color',
        'MEDICARE COLOUR' => 'card_color',
        'CARD COLOUR' => 'card_color',
        'MEDICARE CARD COLOR' => 'card_color',
        'MEDICARE COLOR' => 'card_color',
        'CARD COLOR' => 'card_color',
        'COLOUR' => 'card_color',
        'COLOR' => 'card_color',
    ];
    public const KEY_DATE_OF_BIRTH = [
        'DOB' => 'dob',
        'DATE OF BIRTH' => 'dob',
    ];

    public const DEFAULT_CHECK_NOTE_COUNT = 2;

    /**
     * @var HandleExceptionService
     */
    public HandleExceptionService $exceptionHandler;

    /**
     * Fetch current MRI note data
     * @var string
     */
    public string $mappedNoteData;

    public function __construct()
    {
        $this->exceptionHandler = new HandleExceptionService(self::class);
    }

    public function getIdentificationFields()
    {
        return array_merge(
            self::KEY_DRIVERS_LICENSE_NUMBER,
            self::KEY_DRIVERS_LICENSE_STATE,
            self::KEY_DRIVERS_LICENSE_EXPIRY_DATE,
            self::KEY_PASSPORT_NUMBER,
            self::KEY_PASSPORT_COUNTRY,
            self::KEY_PASSPORT_EXPIRY_DATE,
            self::KEY_MEDICARE_CARD_NUMBER,
            self::KEY_MEDICARE_SPECIAL_NUMBER,
            self::KEY_MEDICARE_CARD_COLOUR,
            self::KEY_MEDICARE_EXPIRY_DATE
        );
    }

    public function getConnectionApplicationFields()
    {
        return array_merge(
            self::KEY_DATE_OF_BIRTH,
        );
    }

    public function getIdentificationTypes()
    {
        $return = [];

        foreach (self::KEY_PASSPORT_NUMBER as $key => $val) {
            $return[$key] = Identification::TYPE_PASSPORT;
        }
        foreach (self::KEY_DRIVERS_LICENSE_NUMBER as $key => $val) {
            $return[$key] = Identification::TYPE_DRIVING_LICENCE;
        }
        foreach (self::KEY_MEDICARE_CARD_NUMBER as $key => $val) {
            $return[$key] = Identification::TYPE_MEDICARE;
        }

        return $return;
    }

    public function getDateFields()
    {
        return array_merge(
            self::KEY_DRIVERS_LICENSE_EXPIRY_DATE,
            self::KEY_MEDICARE_EXPIRY_DATE,
            self::KEY_PASSPORT_EXPIRY_DATE,
            self::KEY_DATE_OF_BIRTH
        );
    }

    public function run()
    {
        $missingService = new NotifyMissingDetailsService();
        $mriApplications = MriApplication::with('connectionApplication')
                            ->where('has_process_note', false)
                            ->where('fetch_notes_count', '<', GetNotesService::getMaxFetchCount())
                            ->get();
        $updatedApplications = [];

        foreach ($mriApplications as $mriApp) {
            $mriApp->fetch_notes_count += 1;
            $mriApp->save();
            $this->mappedNoteData = '';
            $updated = false;
            $conApp = $mriApp->connectionApplication;
            try {
                list($noteAppData, $noteIdentificationData) = $this->mapApplicationNoteFields($mriApp, $conApp);
                if (!empty($noteAppData)) {
                    $conApp->update($noteAppData);
                    $updated = true;
                }

                if (!empty($noteIdentificationData)) {
                    $conApp->identification()->updateOrCreate($noteIdentificationData);
                    $updated = true;
                }
            } catch (\Exception $e) {
                $this->exceptionHandler->addException($e);
            }

            if ($updated) {
                $updatedApplications[] = $conApp->id;
                $mriApp->has_process_note = true;
                $mriApp->save();
                if ($this->validateRequiredFields($noteAppData, $noteIdentificationData)) {
                    $this->deleteNote($conApp, $this->mappedNoteData);
                }
            }

            $notesCount = !empty(config('mri.start_check_notes_count')) ? config('mri.start_check_notes_count') : self::DEFAULT_CHECK_NOTE_COUNT;
            if ($updated || $mriApp->fetch_notes_count == $notesCount) {
                $missingService->check($conApp);
            }
        }

        $missingService->notifyIfAny();

        if ($this->exceptionHandler->hasExceptions()) {
            $this->exceptionHandler->run();
        }

        if (count($updatedApplications) > 0) {
            info(
                sprintf('Updated %s applications from MRI', strval(count($updatedApplications))),
                ['updated_app_ids' => $updatedApplications]
            );
        }
    }

    private function mapApplicationNoteFields($mriApp, $conApp)
    {
        $conAppData = [];
        $identificationData = [];
        $mriNotes = $mriApp->mriNotes()->where('is_checked', false)->orderBy('id', 'desc')->get();

        foreach ($mriNotes as $mriNote) {
            if (!empty($this->mappedNoteData)) {
                break;
            }
            $this->createNote($conApp, $mriNote->description);
            $mriNote->is_checked = true;
            $mriNote->save();
            $single_n_data = preg_replace(array('/\s{2,}/', '/[\t\n\r]+/'), "\n", $mriNote->description);
            $descData = explode("\n", $single_n_data);
            foreach ($descData as $line) {
                $field = explode(":", $line);
                $key = strtoupper(trim($field[0] ?? ''));
                $val = trim($field[1] ?? '');
                if (array_key_exists($key, $this->getConnectionApplicationFields()) && !empty($val)) {
                    $columnName = $this->getConnectionApplicationFields()[$key];
                    if (in_array($key, array_keys($this->getDateFields()))) {
                        $val = $this->getFormattedDate($val);
                    }
                    $conAppData[$columnName] = $val;
                }
                if (array_key_exists($key, $this->getIdentificationFields()) && !empty($val)) {
                    $columnName = $this->getIdentificationFields()[$key];
                    if (in_array($key, array_keys($this->getDateFields()))) {
                        $val = $this->getFormattedDate($val);
                    }
                    $identificationData[$columnName] = $val;
                }
                if (array_key_exists($key, $this->getIdentificationTypes()) && !empty($val)) {
                    $columnName = 'type';
                    $columnVal = $this->getIdentificationTypes()[$key];
                    $identificationData[$columnName] = $columnVal;
                }
            }
            if (!empty($conAppData || !empty($identificationData))) {
                $mriNote->is_fetched = true;
                $mriNote->save();
                $this->mappedNoteData = $mriNote->description;
            }
        }

        $return = [$conAppData, $identificationData];
        return $return;
    }

    private function createNote(ConnectionApplication $conApp, string $noteData)
    {
        $gilbertNoteExist = ApplicationNote::where('connection_application_id', $conApp->id)
                                    ->where('type', ApplicationNote::MRI_IDENTIFICATION)
                                    ->where('text', $noteData)
                                    ->exists();
        if (!$gilbertNoteExist) {
            $createdBy = $conApp->createdBy;
            if (!$createdBy) {
                $user = User::where('email', 'admin@hood.ai')->first();
            } else {
                $user = $createdBy->user;
            }
            $service = new ApplicationNoteService($user);
            $note = [
                'type' => ApplicationNote::MRI_IDENTIFICATION,
                'text' => $noteData,
            ];
            $service->createNotes($note, $conApp->id);
        }
    }

    private function deleteNote(ConnectionApplication $conApp, string $noteData)
    {
        return ApplicationNote::where('connection_application_id', $conApp->id)
                                    ->where('type', ApplicationNote::MRI_IDENTIFICATION)
                                    ->where('text', $noteData)
                                    ->delete();
    }

    private function getFormattedDate(string $date)
    {
        $createFromFormat = 'd/m/Y';
        if (substr_count($date, "/") == 1) {
            $breakDate = explode("/", $date);
            if (strlen($breakDate[1]) == 2) {
                $createFromFormat = 'm/y';
            } else {
                $createFromFormat = 'm/Y';
            }
        }
        try {
            return Carbon::createFromFormat($createFromFormat, $date)->format('Y-m-d');
        } catch (\Exception $e) {
            \Log::error($e->getMessage(), $e->getTrace());
            return $date;
        }
    }

    private function validateRequiredFields($noteAppData, $noteIdentificationData)
    {
        $valid = true;
        $required = $this->getRequiredApplicationFields();
        if (count(array_intersect(array_keys($noteAppData), $required)) != count($required)) {
            $valid = false;
        }
        if (!array_key_exists('type', $noteIdentificationData) || empty($noteIdentificationData['type'])) {
            $valid = false;
        } else {
            $required = $this->getRequiredIdentificationFields($noteIdentificationData['type']);
            if (count(array_intersect(array_keys($noteIdentificationData), $required)) != count($required)) {
                $valid = false;
            }
        }

        return $valid;
    }

    private function getRequiredApplicationFields()
    {
        $required = [
            'dob'
        ];

        return $required;
    }

    private function getRequiredIdentificationFields($type = '')
    {
        $required = [
            'card_number',
            'expire_date'
        ];

        switch ($type) {
            case Identification::TYPE_DRIVING_LICENCE:
                $required[] = 'state';
                break;
            case Identification::TYPE_MEDICARE:
                $required[] = 'card_color';
                $required[] = 'special_number';
                break;
            case Identification::TYPE_PASSPORT:
                $required[] = 'country';
                break;
        }

        return $required;
    }
}
