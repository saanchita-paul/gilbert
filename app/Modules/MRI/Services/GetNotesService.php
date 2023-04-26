<?php

namespace MRI\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Carbon;
use App\Models\MriApplication;
use App\Models\MriNote;
use Illuminate\Database\Eloquent\Builder;
use MRI\Services\LogService;

class GetNotesService
{
    public const CATEGORY_ID = '3db9598a-bafc-ec11-997e-0050f21d26e6';
    public const DEFAULT_GET_NOTES_COUNT = 2;

    /**
     * @var string|null
     */
    private ?string $accessToken;

    /**
     * @var string|null
     */
    private ?string $url;

    /**
     * @var string|null
     */
    private ?string $afterDate;

    /**
     * @var ?int|null
     */
    private ?int $officeId;

    /**
     * @var HandleExceptionService
     */
    public HandleExceptionService $exceptionHandler;

    /**
     * @var LogService
     */
    public LogService $logService;

    public function __construct()
    {
        $this->setURL();
        $this->exceptionHandler = new HandleExceptionService(self::class);
        $this->logService = new LogService();
    }

    public function setAfterDate(string $date)
    {
        $this->afterDate = Carbon::parse($date)->format('Y-m-d');
        return $this;
    }

    public function setOfficeId(int $officeId)
    {
        $this->officeId = $officeId;
    }

    private function setToken(string $token)
    {
        $this->accessToken = $token;
        return $this;
    }

    private function setURL()
    {
        $url = empty(config('mri.base_url')) ? 'https://uatapi.propertytree.io' : config('mri.base_url');
        $endpoint = empty(config('mri.endpoints.get_notes')) ? '/residentialproperty/v1/Notes' : config('mri.endpoints.get_notes');
        $this->url = $url . $endpoint;
        return $this;
    }

    private function getAPIData($token, $profileName)
    {
        $this->setToken($token);

        $headers = [
            'content-type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => 'Bearer ' . $this->accessToken
        ];

        $client = new Client([
            'headers' => $headers,
        ]);

        $query = [
            'profile' => $profileName,
        ];

        if (isset($this->afterDate) && !empty($this->afterDate)) {
            $query['lastModifiedOnOrAfter'] = $this->afterDate;
        }

        $options = [
            'query' => $query
        ];

        $this->logService->create(get_class($this), $this->url, [
            'request_query' => json_encode($query)
        ]);
        $response = $client->request('GET', $this->url, $options);
        $this->logService->update($response);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    public function run()
    {
        try {
            $query = MriApplication::with('mriOffice:id,key')->where('has_process_note', false)->where('fetch_notes_count', '<', self::getMaxFetchCount());
            if (isset($this->officeId) && !empty($this->officeId)) {
                $query->where('mri_office_id', $this->officeId);
            }
            $mriApplications = $query->get();

            foreach ($mriApplications as $mriApp) {
                $token = $mriApp->mriOffice->key;
                $profileName = $mriApp->name;
                $notesData = $this->getAPIData($token, $profileName);
                $this->saveNotes($mriApp, $notesData);
            }
        } catch (RequestException $e) {
            $this->logService->update($e->getResponse());
            $this->exceptionHandler->addException($e);
        } catch (\Exception $e) {
            $this->exceptionHandler->addException($e);
        }

        if ($this->exceptionHandler->hasExceptions()) {
            $this->exceptionHandler->run();
        }
    }

    public function saveNotes(MriApplication|Builder $mriApp, array $notesData)
    {
        $savedNoteIds = [];

        $notesData = array_filter($notesData, function ($nd) use ($mriApp) {
            return $nd['entity_id'] === $mriApp->tenancy_id && !MriNote::where('note_id', $nd['note_id'])->exists();
        });

        foreach ($notesData as $note) {
            try {
                $newMriNote = new MriNote();
                $newMriNote->mri_log_id = $this->logService->getLogId();
                $newMriNote->mri_application_id = $mriApp->id;
                $newMriNote->note_id = $note['note_id'];
                $newMriNote->description = $note['description'];
                $newMriNote->entity_id = $note['entity_id'];
                $newMriNote->entity_type = $note['entity_type'];
                $newMriNote->category_id = $note['category_id'];
                $newMriNote->last_modified = $note['last_modified'];
                $newMriNote->last_modified_by = $note['last_modified_by'];
                $newMriNote->save();

                $savedNoteIds[] = $newMriNote->id;
            } catch (\Exception $e) {
                $data = [
                    'mriApplicationId' => $mriApp->id,
                    'noteData' => $note,
                ];
                $this->exceptionHandler->addException($e, $data);
            }
        }

        if (!empty($savedNoteIds)) {
            $message = sprintf('Created %s mri notes for mri application id %s', count($savedNoteIds), $mriApp->id);
            info($message, ['mri_note_ids' => $savedNoteIds]);
        }

        return $savedNoteIds;
    }

    public static function getMaxFetchCount()
    {
        return !empty(config('mri.max_get_notes_count')) ? config('mri.max_get_notes_count') : self::DEFAULT_GET_NOTES_COUNT;
    }
}
