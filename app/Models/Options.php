<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * id       string
 * options  json_blob
 */
class Options extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false; // Disable auto-incrementing

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
