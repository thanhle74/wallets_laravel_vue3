<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class DashboardControllerApi extends Controller {
    public function getDashboardStats()
    {
        $userId = auth()->id(); // Lấy ID user hiện tại

        // Lấy tổng số tiền trong ngày
        $dailyExpense = DB::table('transactions')
            ->where('user_id', $userId)
            ->whereDate('transaction_date', Carbon::today())
            ->sum(DB::raw("CASE WHEN is_income = 1 THEN amount ELSE -amount END"));

        // Lấy tổng số tiền trong tuần
        $weeklyExpense = DB::table('transactions')
            ->where('user_id', $userId)
            ->whereBetween('transaction_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum(DB::raw("CASE WHEN is_income = 1 THEN amount ELSE -amount END"));

        // Lấy tổng số tiền trong tháng
        $monthlyExpense = DB::table('transactions')
            ->where('user_id', $userId)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->sum(DB::raw("CASE WHEN is_income = 1 THEN amount ELSE -amount END"));

        // Lấy tổng số tiền trong năm
        $yearlyExpense = DB::table('transactions')
            ->where('user_id', $userId)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->sum(DB::raw("CASE WHEN is_income = 1 THEN amount ELSE -amount END"));


        return response()->json([
            'success' => true,
            'message' => 'Successfully',
            'data' => [
                'daily' => $dailyExpense,
                'weekly' => $weeklyExpense,
                'monthly' => $monthlyExpense,
                'yearly' => $yearlyExpense
            ]
        ], Response::HTTP_CREATED);
    }
}
