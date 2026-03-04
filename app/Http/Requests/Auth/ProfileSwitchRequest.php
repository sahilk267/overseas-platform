<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ProfileSwitchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile_id' => ['required', 'integer', 'exists:profiles,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure the profile belongs to the authenticated user
        if ($this->user()) {
            $profile = $this->user()->profiles()->where('id', $this->profile_id)->first();
            
            if (!$profile) {
                abort(403, 'This profile does not belong to you.');
            }
            
            if ($profile->status !== 'active') {
                abort(403, 'This profile is not active.');
            }
        }
    }
}
