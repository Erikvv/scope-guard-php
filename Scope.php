<?php

namespace ScopeGuard;

class Scope
{
    private $success = false;
    private $failureHandlers = [];
    private $successHandlers = [];
    private $exitHandlers = [];
    
    public static function exit(callable $onExit): self {
        $scope = new Scope();
        $scope->onExit($onExit);
        return $scope;
    }

    public function markSuccessful() {
        $this->success = true;
    }

    public function onExit(callable $onExit) {
        $this->exitHandlers[] = $onExit;
    }

    public function onFailure(callable $onFailure) {
        $this->failureHandlers[] = $onFailure;
    }

    public function onSuccess(callable $onSuccess) {
        $this->successHandlers[] = $onSuccess;
    }

    public function executeSuccessHandlers()
    {
        foreach ($this->successHandlers as $handler) {
            $handler();
        }
    }

    /**
     * We would like to pass the current exception to the handler
     * but the engine does not currently support this
     */
    public function executeFailureHandlers()
    {
        foreach ($this->failureHandlers as $handler) {
            $handler();
        }
    }

    public function executeExitHandlers()
    {
        foreach ($this->exitHandlers as $handler) {
            $handler();
        }
    }

    /**
     * The most logical sequence seems to do onExit handlers last,
     * since they are usually for cleanup.
     *
     * Should we catch exceptions?
     */
    public function __destruct()
    {
        if ($this->success) {
            $this->executeSuccessHandlers();
        } else {
            $this->executeFailureHandlers();
        }

        $this->executeExitHandlers();
    }
}
