<?php
declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Modules\Transaction\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Support\Enums\UserRole;
use Carbon\Carbon;
use Modules\Wallet\Models\Wallet;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TransactionController extends BaseControllerApi
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $isAdmin = $user->role->value === UserRole::ADMIN->value;

        $transactions = Transaction::with(['category', 'wallet'])
            ->when($isAdmin, fn($q) => $q->with('user'))
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->when($request->filled('from'), fn($q) =>
            $q->whereDate('transaction_date', '>=', Carbon::parse($request->input('from'))->toDateString())
            )
            ->when($request->filled('to'), fn($q) =>
            $q->whereDate('transaction_date', '<=', Carbon::parse($request->input('to'))->toDateString())
            )
            ->when($request->filled('category'), fn($q) =>
            $q->where('category_id', $request->input('category'))
            )
            ->when($request->filled('wallet'), fn($q) =>
            $q->where('wallet_id', $request->input('wallet'))
            )
            ->when($isAdmin && $request->filled('user_id'), fn($q) =>
            $q->where('user_id', $request->input('user_id'))
            )
            ->get();

        return $this->successResponse($transactions->toArray(), 'Successfully', ResponseAlias::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'is_income' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = $validator->validated();
        $validated['user_id'] = auth()->id();
        $validated['transaction_date'] = Carbon::parse($validated['transaction_date'])->toDateString();

        $isIncome = $validated['is_income'] ?? false;

        $wallet = Wallet::find($validated['wallet_id']);
        if (!$wallet) {
            return $this->errorResponse('Wallet not found', ResponseAlias::HTTP_NOT_FOUND);
        }

        if (!$isIncome) {
            if ($wallet->balance < $validated['amount']) {
                return $this->errorResponse('Insufficient balance in wallet');
            }
            $wallet->balance -= $validated['amount'];
        } else {
            $wallet->balance += $validated['amount'];
        }

        $wallet->save();
        $transaction = Transaction::create($validated);

        return $this->successResponse($transaction->toArray(), 'Successfully', ResponseAlias::HTTP_CREATED);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== auth()->id()) {
            return $this->errorResponse(
                'Unauthorized: You do not have permission to view this transaction.',
                ResponseAlias::HTTP_FORBIDDEN
            );
        }

        return $this->successResponse($transaction->toArray(), 'Transaction retrieved successfully');
    }

    public function update(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== auth()->id()) {
            return $this->errorResponse(
                'Unauthorized: You do not have permission to update this transaction.',
                ResponseAlias::HTTP_FORBIDDEN
            );
        }

        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'wallet_id' => 'sometimes|exists:wallets,id',
            'amount' => 'sometimes|numeric|min:0.01',
            'description' => 'sometimes|string',
            'transaction_date' => 'sometimes|date',
        ]);

        if (empty($validated)) {
            return $this->errorResponse('No changes detected.');
        }

        $transaction->update($validated);

        return $this->successResponse($transaction->toArray(), 'Transaction updated successfully');
    }

    public function destroy(Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return $this->errorResponse(
                'Unauthorized: You do not have permission to delete this transaction.',
                ResponseAlias::HTTP_FORBIDDEN
            );
        }

        $wallet = Wallet::find($transaction->wallet_id);

        if ($wallet) {
            if ($transaction->is_income) {
                $wallet->balance -= $transaction->amount;
            } else {
                $wallet->balance += $transaction->amount;
            }

            $wallet->save();
        }

        $transaction->delete();

        return $this->successResponse([], 'Transaction deleted successfully');
    }

    public function massDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return $this->errorResponse('No users selected or invalid format');
        }

        $ids = array_map('intval', $ids);

        $users = Transaction::whereIn('id', $ids)->get();

        if ($users->isEmpty()) {
            return $this->errorResponse('No users found to delete', ResponseAlias::HTTP_NOT_FOUND);
        }

        Transaction::whereIn('id', $ids)->delete();

        return $this->successResponse(['message' => count($users) . ' users deleted successfully'], 'Transactions deleted successfully');
    }
}
