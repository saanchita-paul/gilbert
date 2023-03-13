<?php

namespace App\Services\Agency;

use App\Models\ApplicationNote;
use App\Models\ConnectionApplication;
use App\Models\HoodProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApplicationNoteService
{
    /** @var User $user */
    private $user;


    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getNotes($application_id)
    {
        return ApplicationNote::query()->where('connection_application_id', '=', $application_id)->get();
    }

    public function createNotes(array $note, $applicationId)
    {

        $note['connection_application_id'] = $applicationId;
        $note['created_by'] = $this->user->id;
        $note['user_role'] = $this->user->roles->first()?->name;
        if (!isset($note['type']) || $note['type'] == ApplicationNote::NOTETYPE['regular']) {
            $note['type'] = ApplicationNote::NOTETYPE['regular'];
            $note['title'] = 'Note by [' . $this->user->profile->first_name . ']';
        } elseif ($note['type'] == ApplicationNote::NOTETYPE['close_connection']) {
            $note['type'] = ApplicationNote::NOTETYPE['close_connection'];
            $note['title'] = 'Note by [' . $this->user->profile->first_name . ']';
        } elseif (in_array($note['type'], ApplicationNote::NOTESUBMIT)) {
            $note['title'] = 'Note by [' . $this->user->profile->first_name . ']';
        } elseif ($note['type'] == ApplicationNote::MRI_IDENTIFICATION) {
            $note['title'] = 'HOOD Data from MRI Note';
        } else {
            $note['title'] = $note['type'];
        }
        return ApplicationNote::create($note);
    }

    /**
     * Creating an assign user note.
     * If assign happen automatically CreatedBy will be 'system'.
     *
     */
    public static function createChatbotAssingNote(int $appId, string $assignUserName, ?string $text = null, array $data = []): void
    {
//        return ApplicationNote::create($note);
        $createdBy = auth()->id() ?? null;
        $title = 'Assigned to ' . $assignUserName;
        $roleText = null;
        $data['name'] = 'System';

        if ($createdBy) {
            /** @var HoodProfile $hoodUser */
            $hoodUser = auth()->user()->profile;
            $role = auth()->user()->roles[0]['name'] ?? '';

            $roleText = ucwords(array_reduce(explode('_', $role), fn($carry, $part) => $carry ? "$carry $part" : $part));
            $name = "{$hoodUser->first_name} $hoodUser->last_name";

            $data = array_merge($data, ['name' => $name, 'role_formatted' => $role]);
        }


        ApplicationNote::create([
            'connection_application_id' => $appId,
            'created_by' => $createdBy,
            'text' => $text,
            'title' => $title,
            'type' => ApplicationNote::ASSIGN_USER,
            'user_role' => $roleText,
            'additional_data' => json_encode($data)
        ]);
    }
}
