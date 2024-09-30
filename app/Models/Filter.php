<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * id       string
 * name     string
 * options  json_blob
 */
class Filter extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    public $incrementing = false; // Disable auto-incrementing

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
