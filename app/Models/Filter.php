<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * id       string
 * name     string
 * options  json_blob
 */
class Filter extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
