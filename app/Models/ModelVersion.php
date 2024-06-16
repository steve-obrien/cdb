<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class ModelVersion extends Model
{
	use HasUlids;
    protected $fillable = ['model_type', 'model_id', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public function versionable()
    {
        return $this->morphTo();
    }
}
