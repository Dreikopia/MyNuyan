<?php

namespace App\Http\Requests;

use App\Enums\ComplaintPriority;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateComplaintCategory extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => [
                'sometimes',
                'max:100',
                Rule::unique('complaint_categories', 'name')
                    ->ignore($category->id),
            ],
            'default_priority' => ['sometimes', Rule::enum(ComplaintPriority::class)],
        ];
    }
}
