<?php
declare(strict_types=1);

namespace Modules\Support\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use App\Http\Controllers\Controller;

abstract class BaseControllerApi extends Controller
{
    protected function successResponse(array $data,string $message = '',int $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse(string $message = '',int $code = ResponseAlias::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
