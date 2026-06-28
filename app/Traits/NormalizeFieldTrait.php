<?php

namespace App\Traits;

trait NormalizeFieldTrait
{
    /**
     * Normalize the given field to lowercase if it exists in the request.
     */
    protected function normalizeFieldToLowercase(string $field): void
    {
        $value = $this->input($field);
        
        if (is_string($value) && $value != "") {
            $this->merge([
                $field => strtolower($value),
            ]);
        }
    }
}
