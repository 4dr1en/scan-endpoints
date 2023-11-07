<?php

namespace App\Rules;

use Closure;
use App\Models\Workspace;
use App\Models\TargetsMonitored;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Test if the endpoint is already registered on the workspace
 */
class NoDuplicateEndpointOnWorkspace implements ValidationRule, DataAwareRule
{
    private array $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $firtElement = TargetsMonitored::where('workspace_id', $this->data['workspace']['id'])
            ->where('protocol', $this->data['protocol'])
            ->where('path', $this->data['path'])
            ->where('port', $this->data['port'])
            ->first();

        if($firtElement) {
            $fail('This endpoint is already registered');
            return;
        }
    }


    public function setData(array $data): void
    {
        $this->data = $data;
    }
}
