<?php
declare(strict_types=1);

namespace Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\AccountManagement\Models\User;
use Modules\Category\Models\Category;
use Modules\Wallet\Models\Wallet;
use Modules\Transaction\Database\Factories\TransactionFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'wallet_id',
        'amount',
        'description',
        'transaction_date',
        'is_income',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    protected static function newFactory(): TransactionFactory
    {
        return TransactionFactory::new();
    }
}
