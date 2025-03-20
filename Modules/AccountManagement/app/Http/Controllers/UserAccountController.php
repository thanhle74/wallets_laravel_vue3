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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

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

    public function getProfile()
    {
        return $this->successResponse(['user' => auth()->user(), 'User profile retrieved successfully']);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = asset("storage/{$path}");
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return $this->successResponse(['user' => $user], 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!\Hash::check($request->current_password, auth()->user()->password)) {
            return $this->errorResponse('Password is incorrect', ResponseAlias::HTTP_BAD_REQUEST);
        }

        auth()->user()->update([
            'password' => bcrypt($request->new_password),
        ]);

        return $this->successResponse([], 'Change password successfully');
    }
}
