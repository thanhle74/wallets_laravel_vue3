<?php
declare(strict_types=1);

namespace Modules\Dashboard\Http\Controllers;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Modules\Support\Http\Controllers\BaseControllerApi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends BaseControllerApi
{
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

        return $this->successResponse(
            [
                'daily' => $dailyExpense,
                'weekly' => $weeklyExpense,
                'monthly' => $monthlyExpense,
                'yearly' => $yearlyExpense
            ],
            'Successfully',
            ResponseAlias::HTTP_CREATED
        );
    }

    public function getMonthlyStats()
    {
        $userId = auth()->id();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Lấy dữ liệu chi phí & thu nhập theo ngày
        $transactions = DB::table('transactions')
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(CASE WHEN is_income = 1 THEN amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN is_income = 0 THEN amount ELSE 0 END) as expenses')
            )
            ->where('user_id', $userId)
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return $this->successResponse($transactions->toArray(), 'Successfully', ResponseAlias::HTTP_CREATED);
    }

    public function getTopCategories()
    {
        $userId = auth()->id();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Lấy top category có tổng chi phí cao nhất trong tháng
        $categories = DB::table('transactions')
            ->select('categories.name', DB::raw('SUM(transactions.amount) as total_spent'))
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('transactions.is_income', 0) // Chỉ lấy chi phí
            ->whereBetween('transactions.transaction_date', [$startOfMonth, $endOfMonth])
            ->groupBy('categories.name')
            ->orderByDesc('total_spent')
            ->limit(5) // Giới hạn 5 category có chi tiêu cao nhất
            ->get();

        return $this->successResponse($categories->toArray(), 'Successfully', ResponseAlias::HTTP_CREATED);
    }


    public function getRecentTransactions()
    {
        $userId = auth()->id();

        $transactions = DB::table('transactions')
            ->select('transactions.*', 'wallets.name as wallet_name', 'categories.name as category_name')
            ->leftJoin('wallets', 'transactions.wallet_id', '=', 'wallets.id')
            ->leftJoin('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->orderByDesc('transactions.transaction_date')
            ->limit(5)
            ->get();

        return $this->successResponse($transactions->toArray(), 'Successfully', ResponseAlias::HTTP_CREATED);
    }

    public function getExpensesByMonths(Request $request)
    {
        $userId = auth()->id();

        $months = $request->input('months', 3);

        $data = DB::table('transactions')
            ->selectRaw('DATE_FORMAT(transaction_date, "%Y-%m") as month,
                     SUM(CASE WHEN is_income = 0 THEN amount ELSE 0 END) as total_expense,
                     SUM(CASE WHEN is_income = 1 THEN amount ELSE 0 END) as total_income')
            ->where('user_id', $userId)
            ->where('transaction_date', '>=', Carbon::now()->subMonths($months - 1)->startOfMonth()) // Lấy N tháng gần nhất
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return $this->successResponse($data->toArray(), 'Successfully', ResponseAlias::HTTP_CREATED);
    }
}
