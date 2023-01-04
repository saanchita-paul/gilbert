<?php

namespace MRI\Services;

use App\Models\MriApplication;
use App\Models\ConnectionApplication;
use App\Services\NotifyBadAgentMailService;
use App\Models\ConnectionService;
use App\Services\Agency\ApplicationNoteService;
use MRI\Services\NotifyMissingDetailsService;
use App\Models\User;
use App\Models\ApplicationNote;

class MapNoteService
{
    /**
     * @var HandleExceptionService
     */
    public HandleExceptionService $exceptionHandler;

    /**
     * Fetch current MRI note data
     * @var string
     */
    public string $noteData;

    public function __construct()
    {
        $this->exceptionHandler = new HandleExceptionService(self::class);
    }

    public function run()
    {
        $missingService = new NotifyMissingDetailsService();
        $mriApplications = MriApplication::with('connectionApplication')
                            ->where('has_process_note', false)
                            ->get();
        $updatedApplications = [];

        foreach ($mriApplications as $mriApp) {
            $mriApp->fetch_notes_count += 1;
            $mriApp->save();
            $this->noteData = '';
            $updated = false;
            $conApp = $mriApp->connectionApplication;
            try {
                list($noteAppData, $noteIdentificationData) = $this->mapApplicationNoteFields($mriApp);
                if (!empty($noteAppData)) {
                    $conApp->update($noteAppData);
                    $updated = true;
                }

                if (!empty($noteIdentificationData)) {
                    $conApp->identification()->updateOrCreate($noteIdentificationData);
                    $updated = true;
                }
            } catch (\Exception $e) {
                $gilbertNoteExist = ApplicationNote::where('connection_application_id', $conApp->id)
                                    ->where('type', ApplicationNote::MRI_IDENTIFICATION)
                                    ->where('text', $this->noteData)
                                    ->exists();
                if (!empty($this->noteData) && !$gilbertNoteExist) {
                    $this->createNote($conApp);
                }
                $this->exceptionHandler->addException($e);
            }

            if ($updated) {
                $updatedApplications[] = $conApp->id;
                $mriApp->has_process_note = true;
                $mriApp->save();
            }

            if ($updated || $mriApp->fetch_notes_count == 2) {
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

    private function mapApplicationNoteFields($mriApp)
    {
        $conAppData = [];
        $identificationData = [];
        $mriNoteData = $mriApp->mriNoteData;

        if ($mriNoteData) {
            $this->noteData = $mriNoteData->description;
            $descData = explode("\n", $mriNoteData->description);
            if (trim($descData[0] ?? '') === 'HOOD_DATA') {
                array_shift($descData);
                foreach ($descData as $line) {
                    $field = explode(":", $line);
                    $key = strtoupper(trim($field[0] ?? ''));
                    $val = trim($field[1] ?? '');
                    if (array_key_exists($key, MriApplication::CONNECTION_APPLICATION_FIELDS) && !empty($val)) {
                        $columnName = MriApplication::CONNECTION_APPLICATION_FIELDS[$key];
                        $conAppData[$columnName] = $val;
                    }
                    if (array_key_exists($key, MriApplication::IDENTIFICATION_FIELDS) && !empty($val)) {
                        $columnName = MriApplication::IDENTIFICATION_FIELDS[$key];
                        $identificationData[$columnName] = $val;
                    }
                    if (array_key_exists($key, MriApplication::IDENTIFICATION_TYPE) && !empty($val)) {
                        $columnName = 'type';
                        $columnVal = MriApplication::IDENTIFICATION_TYPE[$key];
                        $identificationData[$columnName] = $columnVal;
                    }
                }
            }
        }
        $return = [$conAppData, $identificationData];
        return $return;
    }

    private function createNote(ConnectionApplication $conApp)
    {
        $createdBy = $conApp->createdBy;
        if (!$createdBy) {
            $user = User::where('email', 'admin@hood.ai')->first();
        } else {
            $user = $createdBy->user;
        }
        $service = new ApplicationNoteService($user);
        $note = [
            'type' => ApplicationNote::MRI_IDENTIFICATION,
            'text' => $this->noteData,
        ];
        $service->createNotes($note, $conApp->id);
    }
}
