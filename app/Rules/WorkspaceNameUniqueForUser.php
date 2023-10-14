<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WorkspaceNameUniqueForUser implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Do the user is authentified
        if (!auth()->check()) {
            $fail(__('Not authentificated.'));
        }

        if (auth()->user()->workspaces()->where('name', $value)->exists()) {
            $fail(__('You already have access to a workspace with this name.'));
        }
    }
}