<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class AuthManagementApi extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $role = $user->role->value;

        if ($role === UserRole::ADMIN->value) {
            $categories = Category::with('user')->get();
        } else {
            $categories = Category::with('user')->where('user_id', $user->id)->get();
        }

        return response()->json([
            'success' => true,
            'message' => $user->role,
            'data' => $categories
        ], Response::HTTP_OK);
    }

    public function isAdmin(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'data' => null
            ], Response::HTTP_UNAUTHORIZED);
        }

        $isAdmin = $user->role->value === UserRole::ADMIN->value;

        return response()->json([
            'success' => true,
            'message' => $isAdmin ? 'User is an admin' : 'User is not an admin',
            'data' => [
                'user_id' => $user->id,
                'role' => $user->role->value,
                'is_admin' => $isAdmin
            ]
        ], Response::HTTP_OK);
    }
}
