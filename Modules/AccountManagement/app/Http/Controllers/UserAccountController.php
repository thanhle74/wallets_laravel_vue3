<?php
declare(strict_types=1);

namespace Modules\AccountManagement\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Modules\Support\Enums\UserRole;
use Modules\AccountManagement\Models\User;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserAccountController extends BaseControllerApi
{
    public function isAdmin(): JsonResponse
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            return $this->successResponse(['is_admin' => false], 'User is not authenticated');
        }

        return $this->successResponse([
            'is_admin' => $user->role->value === UserRole::ADMIN->value
        ], $user->role->value === UserRole::ADMIN->value ? 'User is an admin' : 'User is not an admin');
    }

    /**
     * Mass delete users.
     */
    public function massDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return $this->errorResponse('No users selected or invalid format');
        }

        $ids = array_map('intval', $ids);

        $users = User::whereIn('id', $ids)->get();

        if ($users->isEmpty()) {
            return $this->errorResponse('No users found to delete', ResponseAlias::HTTP_NOT_FOUND);
        }

        User::whereIn('id', $ids)->delete();

        return $this->successResponse(['message' => count($users) . ' users deleted successfully'], 'Users deleted successfully');
    }
}
