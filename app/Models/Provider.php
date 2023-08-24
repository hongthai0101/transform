<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'path',
        'secret',
        'is_authenticate'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_authenticate' => 'boolean'
    ];


    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->secret = self::generateSecret();
        });
    }

    /**
     * @return HasMany
     */
    public function transforms(): HasMany
    {
        return $this->hasMany(Transform::class);
    }

    public static function generateSecret(): string
    {
        $secret = strtoupper(substr(md5(uniqid(rand(), true)), 0, 80));
        if (self::where('secret', $secret)->exists()) {
            return self::generateSecret();
        }
        return $secret;
    }
}
