<?php
declare(strict_types=1);

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Support\Enums\Status;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')->id;

        return [
            'name' => [
                'sometimes',
                'string',
                Rule::unique('categories', 'name')
                    ->where(fn ($query) => $query->where('user_id', auth()->id()))
                    ->ignore($categoryId),
            ],
            'status' => ['sometimes', 'integer', Rule::in([Status::ACTIVE->value, Status::DISABLED->value])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Category name already exists.',
            'status.integer' => 'Invalid status format.',
            'status.in' => 'Invalid status value.',
        ];
    }
}
