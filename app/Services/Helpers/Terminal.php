<?php

namespace App\Services\Helpers;

use Symfony\Component\Console\Output\ConsoleOutput;

class Terminal
{
    private ConsoleOutput $consoleOutput;

    public function __construct()
    {
        $this->consoleOutput = new ConsoleOutput();
    }


    /**
     * Log info
     *
     * @param string $mgs
     *
     * @return void
     */
    public function logInfo(string $mgs): void
    {
        $this->consoleOutput->writeln($mgs);
    }

    /**
     * Logging text to terminal
     *
     * @param string $mgs
     *
     * @return void
     */
    public static function info(string $mgs): void
    {
        (new Terminal())->logInfo($mgs);
    }
}
