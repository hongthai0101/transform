<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transform extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'name',
        'description',
        'transform_type',
        'to_url',
        'to_method',
        'request_transform',
        'response_transform',
        'code',
        'to_response_data_type'
    ];

    protected $casts = [
        'request_transform' => 'array',
        'response_transform' => 'array',
    ];

    /**
     * @return BelongsTo
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
