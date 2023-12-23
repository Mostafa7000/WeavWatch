<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Dress;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCodeInBatch implements ValidationRule
{
    private $batchId;

    public function __construct($batchId)
    {
        $this->batchId = $batchId;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(Dress::query()
            ->where('code', $value)
            ->where('batch_id', $this->batchId)
            ->exists()) {
            $fail($this->message());
        }
    }

    public function message(): string
    {
        return __('validation.unique_dress');
    }

}
