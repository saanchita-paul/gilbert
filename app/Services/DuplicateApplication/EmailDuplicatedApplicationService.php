<?php


namespace App\Services\DuplicateApplication;


use App\Interfaces\DuplicateApplication\DuplicateApplicationInterface;
use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Builder;


class EmailDuplicatedApplicationService implements DuplicateApplicationInterface
{
    /**
     * @var string
     */
    private string $email;

    /**
     * @var Builder
     */
    private $builder;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return Builder
     */
    public function getBuilder(): Builder
    {
       return ConnectionApplication::whereRaw("REPLACE(`email`, ' ', '') = ? ", $this->email);
    }

}
