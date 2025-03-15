<?php
declare(strict_types=1);

namespace ThanhAloha\AccountManagement\Http\Controllers;

use ThanhAloha\Support\Http\Controllers\BaseControllerApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use ThanhAloha\Support\Enums\UserRole;

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
}
