<?php


namespace App\Services\DuplicateApplication;


use App\Interfaces\DuplicateApplication\DuplicateApplicationInterface;
use App\Models\ConnectionApplication;
use Illuminate\Database\Query\Builder;
use function PHPUnit\Framework\isNull;

class FindEmailDuplicationApplication implements DuplicateApplicationInterface
{
    /**
     * @var string
     */
    private string $email;

    /**
     * @var Builder
     */
    private $builder;
    /**
     * @var mixed
     */
    private $duplicated_email_group_id;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function handle(): void
    {
        $this->findEmailDuplicationApplicationBuilder()
            ->findDuplicatedApplicationGroupId()
            ->updateDuplicationGroupId()
        ;
    }

    public function updateDuplicationGroupId():static
    {
        $this->builder->update(['duplicated_email_group_id'=>  $this->duplicated_email_group_id]);
        return $this;
    }

    /**
     * this method return expected list of
     * @return \static
     */
    public function findDuplicatedApplicationGroupId(): static
    {
        $this->duplicated_email_group_id = $this->builder->whereNotNull('duplicated_email_group_id')->pluck('duplicated_email_group_id')->first();
        if(isNull($this->duplicated_email_group_id)) {
            $this->duplicated_email_group_id = $this->builder->pluck('id')->first();
        }
        return $this;
    }

    private function findEmailDuplicationApplicationBuilder(): static
    {
       $this->builder =  ConnectionApplication::whereRaw("lower(REPLACE(`email`, ' ', '')) = ? ", $this->email);
       return $this;
    }

}
