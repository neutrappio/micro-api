<?php

namespace Mapi\Traits;

/**
 * This trait will help to have unique response keys
 */
trait TResponse
{
    
    /**
     * Get Any Data as one item response
     *
     * @param [type] $data
     * @return array
     */
    public function getAsItem($data) : array
    {
        return [
            'item'=> $data
        ];
    }

    /**
     * Get an array as (many) items response
     *
     * @param array $items
     * @return array
     */
    public function getAsItems(array $items) : array
    {
        return [
            'items'=> $items,
            'count'=> count($items)
        ];
    }
}
