<?php
declare(strict_types=1);

namespace Modules\Wallet\Http\Controllers;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Wallet\Models\Wallet;
use Modules\Support\Enums\UserRole;
use Modules\Wallet\Http\Requests\WalletStoreRequest;
use Modules\Wallet\Http\Requests\WalletUpdateRequest;

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

    public function store(WalletStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        try {
            $wallet = Wallet::create($validated);

            return $this->successResponse(
                $wallet->toArray(),
                'Wallet created successfully',
                ResponseAlias::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to create wallet. ' . $e->getMessage(),
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
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

    public function update(WalletUpdateRequest $request, Wallet $wallet): JsonResponse
    {
        if ($wallet->user_id !== auth()->id()) {
            return $this->errorResponse(
                'Unauthorized: You do not have permission to update this wallet.',
                ResponseAlias::HTTP_FORBIDDEN
            );
        }

        $validated = $request->validated();

        if (empty($validated)) {
            return $this->errorResponse('No changes detected.');
        }

        try {
            $wallet->update($validated);
            return $this->successResponse($wallet->toArray(), 'Wallet updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Wallet $wallet): JsonResponse
    {
        if ($wallet->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            $this->errorResponse(
                'Unauthorized: You do not have permission to delete this wallet.',
                ResponseAlias::HTTP_FORBIDDEN
            );
        }

        $wallet->delete();

        return $this->successResponse([], 'Wallet deleted successfully');
    }
}
