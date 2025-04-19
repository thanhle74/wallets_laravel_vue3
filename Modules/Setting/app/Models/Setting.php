<?php
declare(strict_types=1);

namespace Modules\Setting\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    use HasFactory;

    protected $fillable = ['group', 'key', 'value', 'type', 'label'];
}
