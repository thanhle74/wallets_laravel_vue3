<?php
declare(strict_types=1);

namespace Modules\AccountManagement\Http\Controllers;

use Modules\Support\Http\Controllers\BaseControllerApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Carbon\Carbon;
use Modules\Setting\Models\Setting;

class AuthController extends BaseControllerApi
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            $tokenRecord = $user->tokens()->latest()->first();

            if (!$tokenRecord) {
                return $this->errorResponse('Failed to create token.', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }

            $timeToken = Setting::where('key', 'time_token')->first()->value ?? 2;
            $tokenRecord->expires_at = Carbon::now()->addHours((int)$timeToken);
            $tokenRecord->save();

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'token' => $token,
            ], ResponseAlias::HTTP_OK);
        }

        return $this->errorResponse('Invalid email or password.', ResponseAlias::HTTP_UNAUTHORIZED);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return $this->errorResponse('Unauthorized or invalid token', ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $user->tokens()->delete();

        return $this->successResponse([],'You have successfully logged out.');
    }
}
