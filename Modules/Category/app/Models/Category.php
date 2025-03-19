<?php
declare(strict_types=1);

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Support\Enums\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\AccountManagement\Models\User;
use Modules\Category\Database\Factories\CategoryFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'user_id'];

    protected $casts = [
        'status' => Status::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
