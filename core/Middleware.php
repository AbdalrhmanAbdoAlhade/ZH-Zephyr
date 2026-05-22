<?php

namespace Core;

interface Middleware
{
    /**
     * Handle the incoming request.
     * Return true to continue, or call Response:: and return false to abort.
     */
    public function handle(): bool;
}