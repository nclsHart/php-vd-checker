<?php

namespace PhpVdChecker;

class Reporter
{
    /**
     * @var array
     */
    private $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    /**
     * @param Error $error
     * @param Scope $scope
     */
    public function addError(Error $error, Scope $scope)
    {
        $this->errors[$scope->getFile()->getPathname()][] = $error;
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return Error[]
     */
    public function getErrorsForFile(\SplFileInfo $file): array
    {
        if (!isset($this->errors[$file->getPathname()])) {
            return [];
        }

        return $this->errors[$file->getPathname()];
    }

    /**
     * @return int
     */
    public function countErrors(): int
    {
        $count = 0;
        foreach ($this->errors as $errorsPerFile) {
            $count += count($errorsPerFile);
        }

        return $count;
    }
}