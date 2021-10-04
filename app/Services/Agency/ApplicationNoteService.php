<?php


namespace App\Services\Agency;


use App\Models\ApplicationNote;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApplicationNoteService
{
    /** @var User $user */
    private $user;

    const ESCALATED = 'escalated';
    const CONFIRM_CONNECTION = 'confirmed_connection';
    const CLOSE_CONNECTION = 'close_connection';
    const REGULAR = 'regular';

    const NOTETYPE = [

        'escalated' => self::ESCALATED,
        'confirm_connection' => self::CONFIRM_CONNECTION,
        'confirm_connection' => self::CLOSE_CONNECTION,
        'regular' => self::REGULAR,

    ];

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
        if(!isset($note['type']) || $note['type'] == self::NOTETYPE['regular']) {
            $note['type'] = self::NOTETYPE['regular'];
            $note['title'] = 'Note by ['.$this->user->profile->first_name.']';
        } else {
            $note['title'] = $note['type'];
        }
        return ApplicationNote::create($note);

    }
}
