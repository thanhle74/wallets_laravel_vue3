<?php
declare(strict_types=1);

namespace Modules\FixedExpense\Http\Controllers;

use Modules\FixedExpense\Models\FixedExpenseTemplate;
use Modules\FixedExpense\Models\FixedExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Modules\Support\Enums\UserRole;


class FixedExpenseTemplateApiController extends BaseControllerApi
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->role->value === UserRole::ADMIN->value;

        // Lấy tháng từ request hoặc mặc định là tháng hiện tại
        $month = $request->input('month', Carbon::now()->format('m-Y'));

        // Lấy danh sách template_id đã được sử dụng trong tháng hiện tại
        $usedTemplateIds = FixedExpense::where('user_id', $user->id)
            ->where('month', $month)
            ->whereNotNull('template_id')
            ->pluck('template_id');

        // Lấy các template chưa được dùng trong tháng này, eager load 'user'
        $templates = FixedExpenseTemplate::with('user')  // Eager load 'user' liên quan
        ->when(!$isAdmin, fn($query) => $query->where('user_id', $user->id)) // Nếu không phải admin, chỉ lấy templates của user đó
        ->whereNotIn('id', $usedTemplateIds) // Lọc các template chưa được sử dụng
        ->get();

        // Trả về dữ liệu với templates và thông tin người dùng
        return $this->successResponse($templates->toArray(), 'Lấy danh sách mẫu chi phí cố định thành công');
    }

    // Tạo mới một mẫu chi phí cố định
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $template = FixedExpenseTemplate::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'amount' => $request->amount,
        ]);

        return $this->successResponse($template->toArray(), 'Tạo mẫu chi phí cố định thành công', ResponseAlias::HTTP_CREATED);
    }

    // Lấy thông tin chi tiết của một mẫu chi phí cố định
    public function show($id)
    {
        $template = FixedExpenseTemplate::find($id);

        if (!$template) {
            return $this->errorResponse('Không tìm thấy mẫu chi phí cố định', ResponseAlias::HTTP_NOT_FOUND);
        }

        return $this->successResponse($template->toArray(), 'Lấy thông tin mẫu chi phí cố định thành công');
    }

    // Cập nhật thông tin của mẫu chi phí cố định
    public function update(Request $request, $id)
    {
        $template = FixedExpenseTemplate::find($id);

        if (!$template) {
            return $this->errorResponse('Không tìm thấy mẫu chi phí cố định', ResponseAlias::HTTP_NOT_FOUND);
        }

        $template->name = $request->name ?? $template->name;
        $template->amount = $request->amount ?? $template->amount;
        $template->save();

        return $this->successResponse($template->toArray(), 'Cập nhật mẫu chi phí cố định thành công');
    }

    // Xóa một mẫu chi phí cố định
    public function destroy($id)
    {
        $template = FixedExpenseTemplate::find($id);

        if (!$template) {
            return $this->errorResponse('Không tìm thấy mẫu chi phí cố định', ResponseAlias::HTTP_NOT_FOUND);
        }

        $template->delete();

        return $this->successResponse([], 'Xóa mẫu chi phí cố định thành công');
    }

    public function assign($id, Request $request)
    {
        $monthInput = $request->input('month', now()->format('m-Y'));

        try {
            if (preg_match('/^\d{4}-\d{2}$/', $monthInput)) {
                $month = Carbon::createFromFormat('Y-m', $monthInput)->format('m-Y');
            } elseif (preg_match('/^\d{2}-\d{4}$/', $monthInput)) {
                $month = $monthInput;
            } else {
                $month = now()->format('m-Y');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi parse tháng khi assign: ' . $e->getMessage());
            $month = now()->format('m-Y');
        }

        $template = FixedExpenseTemplate::findOrFail($id);

        $existingRecord = FixedExpense::where('template_id', $template->id)
            ->where('month', $month)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingRecord) {
            return $this->errorResponse('Template has already been assigned for this month');
        }

        FixedExpense::create([
            'user_id' => Auth::id(),
            'template_id' => $template->id,
            'name' => $template->name,
            'amount' => $template->amount,
            'month' => $month,
            'is_deducted' => false,
        ]);

        return $this->successResponse([], 'Template assigned successfully');
    }

    public function massDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return $this->errorResponse('No users selected or invalid format');
        }

        $ids = array_map('intval', $ids);

        $users = FixedExpenseTemplate::whereIn('id', $ids)->get();

        if ($users->isEmpty()) {
            return $this->errorResponse('No users found to delete', ResponseAlias::HTTP_NOT_FOUND);
        }

        FixedExpenseTemplate::whereIn('id', $ids)->delete();

        return $this->successResponse(['message' => count($users) . ' users deleted successfully'], 'Deleted successfully');
    }
}
