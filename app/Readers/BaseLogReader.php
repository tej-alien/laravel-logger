<?php

namespace App\Readers;

use App\LogFile;
use App\LogLevels\LevelInterface;
use App\Logs\Log;
use App\Concerns;

abstract class BaseLogReader
{
    use \App\Concerns\LogReader\KeepsFileHandle;
    use \App\Concerns\LogReader\KeepsInstances;

    protected LogFile $file;

    /** @var string|Log */
    protected string $logClass;

    /** @var string|LevelInterface */
    protected string $levelClass;

    public function __construct(LogFile $file)
    {
        $this->file = $file;
        $this->logClass = $this->file->type()->logClass() ?? Log::class;
        $this->levelClass = $this->logClass::levelClass();
    }

    protected function makeLog(string $text, int $filePosition, int $index): Log
    {
        return new $this->logClass($text, $this->file->identifier, $filePosition, $index);
    }

    public function __destruct()
    {
        $this->closeFile();
    }
}
