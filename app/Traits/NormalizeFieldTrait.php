<?php

namespace App\Traits;

trait NormalizeFieldTrait
{
    /**
     * Normalize the given field to lowercase if it exists in the request.
     */
    protected function normalizeFieldToLowercase(string $field)
    {
        if ($this->has($field)) {
            $this->merge([
                $field => strtolower($this->input($field)),
            ]);
        }
    }
}
