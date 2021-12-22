<?php


namespace App\Services\Agency;


use App\Models\ApplicationNote;
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
        return ApplicationNote::query()->where('connection_application_id','=', $application_id)->get();
    }

    public function createNotes(array $note, $applicationId)
    {

        $note['connection_application_id'] = $applicationId;
        $note['created_by'] = $this->user->id;
        $note['user_role'] = $this->user->roles->first()?->name;
        if(!isset($note['type']) || $note['type'] == ApplicationNote::NOTETYPE['regular']) {
            $note['type'] = ApplicationNote::NOTETYPE['regular'];
            $note['title'] = 'Note by ['.$this->user->profile->first_name.']';
        }
        else if ( $note['type'] == ApplicationNote::NOTETYPE['close_connection'] ) {
            $note['type'] = ApplicationNote::NOTETYPE['close_connection'];
            $note['title'] = 'Note by ' . $this->user->profile->first_name;
        }
        else if($note['type'] === ApplicationNote::SUBMITTED_CONNECTION) {
            $note['title'] = 'Note by ['.$this->user->profile->first_name.']';
        }
        else {
            $note['title'] = $note['type'];
        }
        return ApplicationNote::create($note);

    }
}
