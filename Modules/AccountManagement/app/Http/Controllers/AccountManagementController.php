<?php
declare(strict_types=1);

namespace Modules\AccountManagement\Http\Controllers;

use Modules\Support\Enums\Status;
use Modules\Support\Enums\UserRole;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Modules\AccountManagement\Models\User;

class AccountManagementController extends BaseControllerApi
{
    public function index(): JsonResponse
    {
        return $this->successResponse(User::all()->toArray(), 'Users retrieved successfully');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'sometimes|string|in:' . implode(',', array_column(UserRole::cases(), 'value')),
            'status'   => 'sometimes|integer|in:' . Status::ACTIVE->value . ',' . Status::DISABLED->value,
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = $validator->validated();
        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = $validated['role'] ?? UserRole::USER->value;
        $validated['status'] = $validated['status'] ?? Status::ACTIVE->value;

        $user = User::create($validated);

        return $this->successResponse($user->toArray(), 'User created successfully');
    }

    public function show(User $user): JsonResponse
    {
        return $this->successResponse($user->toArray(), 'User retrieved successfully');
    }

    public function update(Request $request, User $user): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => 'sometimes|string|max:255',
                'email'    => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:6|confirmed',
                'role'     => 'sometimes|string|in:' . implode(',', array_column(UserRole::cases(), 'value')),
                'status'   => 'sometimes|integer|in:' . Status::ACTIVE->value . ',' . Status::DISABLED->value,
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->first(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }

            $validated = $validator->validated();

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            }

            if (empty($validated)) {
                return $this->errorResponse('No changes detected.');
            }

            $user->update($validated);

            return $this->successResponse($user->toArray(), 'User updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return $this->successResponse([], 'User deleted successfully');
    }
}
