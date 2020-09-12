<?php


namespace App\Resource;


abstract class BaseResource
{
    /**
     * Mass assignments
     *
     * @param array $raw
     */
    protected function fill($raw = []): void
    {
        // TODO find a way to fill sub-resources [falling exception if sub resource not initiated]
        foreach ($raw as $key => $value) {
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Convert resource to array
     *
     * @return array
     */
    protected function toArray()
    {
        $obj = [];
        foreach ($this as $key => $value) {
            $obj[$key] = $this->{$key} instanceof BaseResource
                ? $this->{$key}->toArray()
                : $value;
        }
        return $obj;
    }
}