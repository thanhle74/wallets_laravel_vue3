<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Enums\UserRole;

class TransactionApi extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $role = $user->role->value;

        if ($role === UserRole::ADMIN->value) {
            $transactions = Transaction::with(['category', 'wallet', 'user'])->get();
        } else {
            $transactions = Transaction::with(['category', 'wallet', 'user'])->where('user_id', $user->id)->get();
        }

        return response()->json([
            'success' => true,
            'message' => $user->role,
            'data' => $transactions
        ], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
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
        $transaction = Transaction::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully',
            'data' => $transaction
        ], Response::HTTP_CREATED);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: You do not have permission to view this transaction.'
            ], Response::HTTP_FORBIDDEN);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction retrieved successfully',
            'data' => $transaction
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: You do not have permission to update this transaction.'
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'wallet_id' => 'sometimes|exists:wallets,id',
            'amount' => 'sometimes|numeric|min:0.01',
            'description' => 'sometimes|string',
            'transaction_date' => 'sometimes|date',
        ]);

        if (empty($validated)) {
            return response()->json([
                'success' => false,
                'message' => 'No changes detected.',
                'data' => []
            ], Response::HTTP_BAD_REQUEST);
        }

        $transaction->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully',
            'data' => $transaction
        ], Response::HTTP_OK);
    }

    public function destroy(Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: You do not have permission to delete this transaction.'
            ], Response::HTTP_FORBIDDEN);
        }

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully',
            'data' => null
        ], Response::HTTP_OK);
    }
}
