<?php


namespace App\Services\DuplicateApplication;


use App\Interfaces\DuplicateApplication\DuplicateApplicationInterface;
use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Builder;

class PhoneDuplicationApplicationService implements DuplicateApplicationInterface
{
    /**
     * @var Builder
     */
    private Builder $builder;
    private string $phone;


    public function __construct(string $phone)
    {
        $this->phone = $phone;
    }

    public function getBuilder(): Builder
    {
        $phone = preg_replace('/[^0-9]/', '',$this->phone);
        $position = strlen($phone) - 9;
        return ConnectionApplication::query()
            ->whereRaw("right(replace(replace(replace(replace(phone, ' ', ''), '+',''), '-',''),'_',''), 9) = ?",
                substr($phone, $position))
            ->orWhereRaw(
                "right(replace(replace(replace(replace(homephone, ' ', ''), '+',''), '-',''),'_',''), 9) = ?",
                substr($phone, $position)
            );
    }
}
