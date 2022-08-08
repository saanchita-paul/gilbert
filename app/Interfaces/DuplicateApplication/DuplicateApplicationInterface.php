<?php


namespace App\Interfaces\DuplicateApplication;


interface DuplicateApplicationInterface
{


    /**
     * initial point of every duplicate finding
     */
    public function handle(): void;

    /**
     * save duplication group id after finding new duplicated application
     * @return void
     */
    public function updateDuplicationGroupId(): static;

    /**
     * @return mixed
     */
    public function findDuplicatedApplicationGroupId(): static;
}
