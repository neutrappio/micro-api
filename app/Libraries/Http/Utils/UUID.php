<?php

namespace Mapi\Utils;

trait UUID
{
    /**
     * Get UUID Pattern
     *
     * - Helper to use on routes
     *
     * @return string
     */
    public static function getPattern() : string
    {
        return "[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}";
    }
}
