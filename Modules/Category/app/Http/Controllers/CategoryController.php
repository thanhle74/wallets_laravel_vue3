<?php
declare(strict_types=1);

namespace Modules\Category\Http\Controllers;

use Modules\Support\Http\Controllers\BaseControllerApi;
use Modules\Category\Models\Category;
use Modules\Support\Enums\UserRole;
use Modules\Category\Http\Requests\CategoryStoreRequest;
use Modules\Category\Http\Requests\CategoryUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryController extends BaseControllerApi
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $role = $user->role->value;

        $categories = $role === UserRole::ADMIN->value
            ? Category::with('user')->get()
            : Category::where('user_id', $user->id)->get();

        return $this->successResponse($categories->toArray(), 'Successfully', ResponseAlias::HTTP_CREATED);
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = auth()->id();

            $category = Category::create($validated);

            return $this->successResponse($category->toArray(), 'Category created successfully', ResponseAlias::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Category $category): JsonResponse
    {
        return $this->successResponse($category->toArray(), 'Category retrieved successfully');
    }

    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        try {
            if ($category->user_id !== auth()->id()) {
                return $this->errorResponse(
                    'Unauthorized: You do not have permission to update this category.',
                    ResponseAlias::HTTP_FORBIDDEN
                );
            }

            $validated = $request->validated();

            if (empty($validated)) {
                return $this->errorResponse('No changes detected.');
            }

            $category->update($validated);

            return $this->successResponse($category->toArray(), 'Category updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return $this->errorResponse(
                'Unauthorized: You do not have permission to delete this category.',
                ResponseAlias::HTTP_FORBIDDEN
            );
        }

        $category->delete();

        return $this->successResponse($category->toArray(), 'Category deleted successfully');
    }
}
