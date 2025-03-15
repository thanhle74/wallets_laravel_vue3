<?php
declare(strict_types=1);

namespace ThanhAloha\AccountManagement\Http\Controllers;

use App\Enums\Status;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use ThanhAloha\AccountManagement\Models\User;

class AccountManagementApiController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users
        ], ResponseAlias::HTTP_OK);
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
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'data' => []
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated = $validator->validated();
        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = $validated['role'] ?? UserRole::USER->value;
        $validated['status'] = $validated['status'] ?? Status::ACTIVE->value;

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], ResponseAlias::HTTP_CREATED);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => $user
        ], ResponseAlias::HTTP_OK);
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
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                    'data' => []
                ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }

            $validated = $validator->validated();

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            }

            if (empty($validated)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No changes detected.',
                    'data' => []
                ], ResponseAlias::HTTP_BAD_REQUEST);
            }

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ], ResponseAlias::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
            'data' => null
        ], ResponseAlias::HTTP_OK);
    }
}
