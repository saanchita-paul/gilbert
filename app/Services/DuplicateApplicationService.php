<?php


namespace App\Services;


use App\Models\ConnectionApplication;

class DuplicateApplicationService
{
    private string $duplicate_group_id;

    /**
     * DuplicateApplicationService constructor.
     * @param $duplicate_group_id
     */
    public function __construct(string $duplicate_group_id)
    {
        $this->duplicate_group_id = $duplicate_group_id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function get(): \Illuminate\Database\Eloquent\Collection|array
    {
        return ConnectionApplication::query()->where('duplication_group_id', $this->duplicate_group_id)->get();
    }
}
