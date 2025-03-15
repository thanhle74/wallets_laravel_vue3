<?php
declare(strict_types=1);

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Support\Enums\Status;

class CategoryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('categories', 'name')->where(function ($query) {
                    return $query->where('user_id', auth()->id()); // Chỉ unique theo user_id
                }),
            ],
            'status' => ['integer', Rule::in([Status::ACTIVE->value, Status::DISABLED->value])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.unique' => 'You already have a category with this name.',
            'status.integer' => 'Invalid status format.',
            'status.in' => 'Invalid status value.',
        ];
    }
}
