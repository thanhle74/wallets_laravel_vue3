<?php
declare(strict_types=1);

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Support\Enums\Type;
use Modules\Support\Enums\Status;

class WalletUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $walletId = $this->route('wallet')->id ?? null;
        $userId = $this->route('wallet')->user_id ?? auth()->id();

        return [
            'name' => [
                'sometimes',
                'string',
                Rule::unique('wallets', 'name')
                    ->where(fn($query) => $query->where('user_id', $userId))
                    ->ignore($walletId),
            ],
            'balance' => 'sometimes|numeric|min:0',
            'type' => ['sometimes', Rule::in([Type::CASH->value, Type::BANK->value, Type::CRYPTO->value])],
            'status' => ['sometimes', Rule::in([Status::ACTIVE->value, Status::DISABLED->value])],
        ];
    }
}
