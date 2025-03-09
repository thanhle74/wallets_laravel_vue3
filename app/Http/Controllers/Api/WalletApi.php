<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Enums\Status;
use App\Enums\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Enums\UserRole;

class WalletApi extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $role = $user->role->value;

        if ($role === UserRole::ADMIN->value) {
            $wallets = Wallet::with('user')->get();
        } else {
            $wallets = Wallet::with('user')->where('user_id', $user->id)->get();
        }

        return response()->json([
            'success' => true,
            'message' => $user->role,
            'data' => $wallets
        ], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('wallets', 'name')->where(fn($query) => $query->where('user_id', auth()->id()))
            ],
            'balance' => 'nullable|numeric|min:0',
            'type' => ['required', Rule::in([Type::CASH->value, Type::BANK->value])],
            'status' => ['integer', Rule::in([Status::ACTIVE->value, Status::DISABLED->value])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => []
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = $validator->validated();
        $validated['user_id'] = auth()->id();
        $wallet = Wallet::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Wallet created successfully',
            'data' => $wallet
        ], Response::HTTP_CREATED);
    }

    public function show(Wallet $wallet): JsonResponse
    {
        if ($wallet->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: You do not have permission to view this wallet.'
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'success' => true,
            'message' => 'Wallet retrieved successfully',
            'data' => $wallet
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Wallet $wallet): JsonResponse
    {
        try {
            if ($wallet->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: You do not have permission to update this wallet.'
                ], Response::HTTP_FORBIDDEN);
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
                return response()->json([
                    'success' => false,
                    'message' => 'No changes detected.',
                    'data' => []
                ], Response::HTTP_BAD_REQUEST);
            }

            $wallet->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Wallet updated successfully',
                'data' => $wallet
            ], Response::HTTP_OK);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
                'data' => []
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Wallet $wallet): JsonResponse
    {
        if ($wallet->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: You do not have permission to delete this wallet.'
            ], Response::HTTP_FORBIDDEN);
        }

        $wallet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wallet deleted successfully',
            'data' => null
        ], Response::HTTP_OK);
    }
}
