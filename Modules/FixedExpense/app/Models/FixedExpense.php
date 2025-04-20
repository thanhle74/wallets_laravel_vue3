<?php
declare(strict_types=1);

namespace Modules\FixedExpense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\AccountManagement\Models\User;

class FixedExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'name',
        'amount',
        'month',
        'is_deducted',
    ];

    protected $casts = [
        'is_deducted' => 'boolean',
    ];

    // Relationship with User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with FixedExpenseTemplate
    public function template(): BelongsTo
    {
        return $this->belongsTo(FixedExpenseTemplate::class);
    }
}
