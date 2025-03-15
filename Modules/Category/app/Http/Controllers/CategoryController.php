<?php
declare(strict_types=1);

namespace Modules\Category\Http\Controllers;

use Modules\Support\Http\Controllers\BaseControllerApi;
use Modules\Category\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\Support\Enums\Status;
use Modules\Support\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class CategoryController extends BaseControllerApi
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

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('categories', 'name')->where(fn($query) => $query->where('user_id', auth()->id()))
            ],
            'status' => 'integer|in:' . Status::ACTIVE->value . ',' . Status::DISABLED->value,
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
        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], Response::HTTP_CREATED);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Category retrieved successfully',
            'data' => $category
        ], Response::HTTP_OK);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        try {
            if ($category->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: You do not have permission to update this category.'
                ], Response::HTTP_FORBIDDEN);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|unique:categories,name,' . $category->id,
                'status' => 'sometimes|integer|in:' . Status::ACTIVE->value . ',' . Status::DISABLED->value,
            ]);

            if (empty($validated)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No changes detected.',
                    'data' => []
                ], Response::HTTP_BAD_REQUEST);
            }

            $category->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category
            ], Response::HTTP_OK);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
                'data' => []
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
            'data' => null
        ], Response::HTTP_OK);
    }
}
