<?php
declare(strict_types=1);

namespace Modules\FixedExpense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FixedExpenseTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function fixedExpenses(): HasMany
    {
        return $this->hasMany(FixedExpense::class);
    }
}
