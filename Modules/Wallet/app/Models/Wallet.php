<?php
declare(strict_types=1);

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Support\Enums\Type;
use Modules\Support\Enums\Status;
use Modules\AccountManagement\Models\User;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'balance',
        'type',
        'user_id',
        'status',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'type' => Type::class,
        'status' => Status::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
