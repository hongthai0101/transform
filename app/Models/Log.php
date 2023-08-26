<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'transform_id',
        'type',
        'inputs',
        'outputs'
    ];

    protected $casts = [
        'inputs' => 'array',
        'outputs' => 'array'
    ];

    public function transform()
    {
        return $this->belongsTo(Transform::class);
    }
}
