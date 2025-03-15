<?php
declare(strict_types=1);

namespace ThanhAloha\Wallet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ThanhAloha\Support\Enums\Type;
use ThanhAloha\Support\Enums\Status;
use ThanhAloha\AccountManagement\Models\User;
class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallets';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

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
