<?php
declare(strict_types=1);

namespace Modules\FixedExpense\Http\Controllers;

use Illuminate\Http\Request;
use Modules\FixedExpense\Models\FixedExpense;
use Modules\FixedExpense\Models\FixedExpenseTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FixedExpenseApiController extends Controller
{
    // Lấy tất cả chi phí cố định của người dùng hiện tại
    public function index()
    {
        $fixedExpenses = FixedExpense::where('user_id', Auth::id())->get();

        // Trả về dữ liệu dưới dạng JSON
        return response()->json($fixedExpenses, 200);
    }

    // Lấy danh sách các template chi phí cố định
    public function create()
    {
        $templates = FixedExpenseTemplate::all();

        return response()->json($templates, 200);
    }

    // Tạo mới chi phí cố định
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|exists:fixed_expense_templates,id',
            'month' => 'required|date_format:Y-m',
            'is_deducted' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Lưu chi phí cố định cho người dùng
        $fixedExpense = FixedExpense::create([
            'user_id' => Auth::id(),
            'template_id' => $request->template_id,
            'month' => $request->month,
            'is_deducted' => $request->is_deducted ?? false,
        ]);

        return response()->json($fixedExpense, 201); // Trả về chi phí cố định vừa tạo
    }

    // Lấy thông tin chi tiết của một chi phí cố định
    public function show(FixedExpense $fixedExpense)
    {
        return response()->json($fixedExpense, 200);
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
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Cập nhật chi phí cố định
        $fixedExpense->update([
            'template_id' => $request->template_id,
            'month' => $request->month,
            'is_deducted' => $request->is_deducted ?? false,
        ]);

        return response()->json($fixedExpense, 200); // Trả về chi phí cố định đã được cập nhật
    }

    // Xóa chi phí cố định
    public function destroy(FixedExpense $fixedExpense)
    {
        $fixedExpense->delete();

        return response()->json(['message' => 'Fixed Expense deleted successfully'], 200);
    }
}
