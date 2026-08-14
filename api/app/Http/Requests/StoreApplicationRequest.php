<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('phone')) {
            $digits = preg_replace('/\D/', '', (string) $this->input('phone'));

            $this->merge(['phone' => $digits]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'regex:/^998\d{9}$/'],
            'theme' => ['required', 'integer', Rule::exists('application_themes', 'id')->where('status', true)],
            'message' => ['required', 'string', 'max:500'],
        ];
    }

    /**
     * The dump keeps its numbers as "+998 (XX) XXX-XX-XX", so new rows
     * follow the same shape.
     */
    public function formattedPhone(): string
    {
        $local = substr((string) $this->validated('phone'), 3);

        return sprintf(
            '+998 (%s) %s-%s-%s',
            substr($local, 0, 2),
            substr($local, 2, 3),
            substr($local, 5, 2),
            substr($local, 7, 2),
        );
    }
}
