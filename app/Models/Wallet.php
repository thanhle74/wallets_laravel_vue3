<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\Type;
use App\Enums\Status;

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
