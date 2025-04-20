<?php
declare(strict_types=1);

namespace Modules\FixedExpense\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\FixedExpense\Models\FixedExpense;
use Modules\FixedExpense\Models\FixedExpenseTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Modules\Support\Enums\UserRole;

class FixedExpenseApiController extends BaseControllerApi
{
    // Lấy tất cả chi phí cố định của người dùng hiện tại
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role->value === UserRole::ADMIN->value;

        // Khởi tạo query với eager loading user info
        $query = FixedExpense::with('user'); // Giả sử có relationship 'user' trong model

        // Nếu không phải admin, chỉ lấy của user hiện tại
        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        // Lọc theo tháng
        $month = $request->input('month', now()->format('m-Y'));
        $query->where('month', $month);

        $fixedExpenses = $query->get();

        return $this->successResponse($fixedExpenses->toArray());
    }

    // Lấy danh sách các template chi phí cố định
    public function create()
    {
        $templates = FixedExpenseTemplate::all();

        if ($templates->isEmpty()) {
            return response()->json(['message' => 'Không có template nào'], ResponseAlias::HTTP_NOT_FOUND);
        }

        return $this->successResponse($templates->toArray());
    }

    // Tạo mới chi phí cố định
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|exists:fixed_expense_templates,id',
            'month' => 'required|date_format:m-Y',
            'is_deducted' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $template = FixedExpenseTemplate::find($request->template_id);

        if (!$template) {
            $this->errorResponse('Không tìm thấy template', ResponseAlias::HTTP_NOT_FOUND);
        }

        $fixedExpense = FixedExpense::create([
            'user_id' => Auth::id(),
            'template_id' => $template->id,
            'name' => $template->name, // ✳️ Bắt buộc có
            'amount' => $template->amount, // ✳️ Bắt buộc có
            'month' => $request->month,
            'is_deducted' => $request->is_deducted ?? false,
        ]);

        return $this->successResponse($fixedExpense->toArray(), 'Tạo chi phí cố định thành công');
    }


    // Lấy thông tin chi tiết của một chi phí cố định
    public function show(FixedExpense $fixedExpense)
    {
        return $this->successResponse($fixedExpense->toArray());
    }

    // Cập nhật chi phí cố định
    public function update(Request $request, FixedExpense $fixedExpense)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|exists:fixed_expense_templates,id',
            'month' => 'required|date_format:Y-m',
            'is_deducted' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Cập nhật chi phí cố định
        $fixedExpense->update([
            'template_id' => $request->template_id,
            'month' => $request->month,
            'is_deducted' => $request->is_deducted ?? false,
        ]);

        return $this->successResponse($fixedExpense->toArray());
    }

    // Xóa chi phí cố định
    public function destroy(FixedExpense $fixedExpense)
    {
        $fixedExpense->delete();

        return $this->successResponse([],'Xóa chi phí cố định thành công');
    }

    public function massDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return $this->errorResponse('No users selected or invalid format');
        }

        $ids = array_map('intval', $ids);

        $users = FixedExpense::whereIn('id', $ids)->get();

        if ($users->isEmpty()) {
            return $this->errorResponse('No users found to delete', ResponseAlias::HTTP_NOT_FOUND);
        }

        FixedExpense::whereIn('id', $ids)->delete();

        return $this->successResponse(['message' => count($users) . ' users deleted successfully'], 'Deleted successfully');
    }
}
