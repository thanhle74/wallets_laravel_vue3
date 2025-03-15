<?php
declare(strict_types=1);

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Support\Enums\Type;
use Modules\Support\Enums\Status;

class WalletStoreRequest extends FormRequest
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
                Rule::unique('wallets', 'name')->where(fn($query) => $query->where('user_id', auth()->id())),
            ],
            'balance' => 'sometimes|nullable|numeric|min:0',
            'type' => ['required', Rule::in([Type::CASH->value, Type::BANK->value, Type::CRYPTO->value])],
            'status' => ['integer', Rule::in([Status::ACTIVE->value, Status::DISABLED->value])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The wallet name is required.',
            'name.unique' => 'The wallet name has already been taken.',
            'balance.numeric' => 'The balance must be a number.',
            'type.required' => 'The wallet type is required.',
        ];
    }
}
