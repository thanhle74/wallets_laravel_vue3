<?php
declare(strict_types=1);

namespace ThanhAloha\Wallet\Http\Controllers;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use ThanhAloha\Support\Http\Controllers\BaseControllerApi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use ThanhAloha\Wallet\Models\Wallet;
use ThanhAloha\Support\Enums\Type;
use ThanhAloha\Support\Enums\Status;
use ThanhAloha\Support\Enums\UserRole;

class WalletController extends BaseControllerApi
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        if ($user->role->value === UserRole::ADMIN->value) {
            $wallets = Wallet::with('user')->get();
        } else {
            $wallets = Wallet::where('user_id', $user->id)->get();
        }

        return $this->successResponse($wallets->toArray(),'Wallet retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('wallets', 'name')->where(fn($query) => $query->where('user_id', auth()->id()))
            ],
            'balance' => 'sometimes|nullable|numeric|min:0',
            'type' => ['required', Rule::in([Type::CASH->value, Type::BANK->value, Type::CRYPTO->value])],
            'status' => ['integer', Rule::in([Status::ACTIVE->value, Status::DISABLED->value])],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = $validator->validated();
        $validated['user_id'] = auth()->id();
        $wallet = Wallet::create($validated);

        return $this->successResponse(
            $wallet->toArray(),
            'Wallet created successfully',
            ResponseAlias::HTTP_CREATED
        );
    }

    public function show(Wallet $wallet): JsonResponse
    {
        if ($wallet->user_id !== auth()->id()) {
            return $this->errorResponse(
                'Unauthorized: You do not have permission to view this wallet.',
                ResponseAlias::HTTP_FORBIDDEN
            );
        }

        return $this->successResponse($wallet->toArray(), 'Wallet retrieved successfully');
    }

    public function update(Request $request, Wallet $wallet): JsonResponse
    {
        try {
            if ($wallet->user_id !== auth()->id()) {
                return $this->errorResponse(
                    'Unauthorized: You do not have permission to update this wallet.',
                    ResponseAlias::HTTP_FORBIDDEN
                );
            }

            $validated = $request->validate([
                'name' => [
                    'sometimes',
                    'string',
                    Rule::unique('wallets', 'name')
                        ->where(fn ($query) => $query->where('user_id', $wallet->user_id))
                        ->ignore($wallet->id),
                ],
                'balance' => 'sometimes|numeric|min:0',
                'type' => ['sometimes', Rule::in([Type::CASH->value, Type::BANK->value, Type::CRYPTO->value])],
                'status' => ['sometimes', Rule::in([Status::ACTIVE->value, Status::DISABLED->value])],
            ]);

            if (empty($validated)) {
                return $this->errorResponse('No changes detected.');
            }

            $wallet->update($validated);

            return $this->successResponse($wallet->toArray(), 'Wallet updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse($e->getMessage(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Wallet $wallet): JsonResponse
    {

        //dd($wallet);
        if ($wallet->user_id !== auth()->id()) {
            $this->errorResponse(
                'Unauthorized: You do not have permission to delete this wallet.',
                ResponseAlias::HTTP_FORBIDDEN
            );
        }

        $wallet->delete();

        return $this->successResponse([], 'Wallet deleted successfully');
    }
}
