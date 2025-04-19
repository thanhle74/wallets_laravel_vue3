<?php
declare(strict_types=1);

namespace Modules\FixedExpense\Http\Controllers;

use Modules\FixedExpense\Models\FixedExpenseTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FixedExpenseTemplateApiController extends BaseControllerApi
{
    // Lấy danh sách tất cả template chi phí cố định
    public function index()
    {
        $templates = FixedExpenseTemplate::all();
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
}
