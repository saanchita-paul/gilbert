<?php

namespace App\Services\ApplicationCafService;


use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Storage;

class RemoveOldCafService
{
    public function delete($shouldClearTodayAlso =  false)
    {
        $directories = Storage::directories('public/gilbert');
        info("directories", [$directories]);
        foreach ($directories as $dir) {
            $baseName = basename($dir);
            try {
                Storage::deleteDirectory($dir);
                info('CaF file generate at'.$baseName. 'is cleared');
            } catch (\Exception $exception) {
                info('invalid date');
            }

        }
    }
}
